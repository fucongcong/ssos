package Core;

import Core.pack.Data;
import Core.pack.Response;
import Core.util.MethodReflectUtil;
import Core.util.SocketUtil;
import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;
import com.typesafe.config.Config;
import com.typesafe.config.ConfigException;

import java.io.IOException;
import java.lang.reflect.Method;
import java.net.InetSocketAddress;
import java.nio.ByteBuffer;
import java.nio.channels.SelectionKey;
import java.nio.channels.Selector;
import java.nio.channels.ServerSocketChannel;
import java.nio.channels.SocketChannel;
import java.util.Iterator;
import java.util.Set;

public class NioServer {
    private Selector selector;

    protected Context ctx;

    protected int port = 9394;

    protected int bodyOffset = 4;

    protected  int packageMaxLength = 2000000;

    protected int readBufferSize = 1024 * 20;

    protected int writebuffSize = 1024 * 20;

    public NioServer(Context ctx) {
        this.ctx = ctx;
        loadConf();
    }

    public void run() {
        try {
            this.selector = Selector.open();
            //创建一个通道对象channel
            ServerSocketChannel channel = ServerSocketChannel.open();
            channel.configureBlocking(false);
            channel.socket().bind(new InetSocketAddress(port));
            channel.register(selector, SelectionKey.OP_ACCEPT);

            while (true) {
                selector.select();
                Set keys = selector.selectedKeys();         //如果channel有数据了，将生成的key访入keys集合中
                Iterator iterator = keys.iterator();        //得到这个keys集合的迭代器
                while (iterator.hasNext()) {             //使用迭代器遍历集合
                    SelectionKey key = (SelectionKey) iterator.next();       //得到集合中的一个key实例
                    iterator.remove();          //拿到当前key实例之后记得在迭代器中将这个元素删除，非常重要，否则会出错

                    //不使用线程池，来一个开一个线程
                    if (key.isAcceptable()) {         //判断当前key所代表的channel是否在Acceptable状态，如果是就进行接收
                        accept(key);
                    } else if (key.isReadable()) {
                        //丢入线程池
                        read(key);
                    } else if (key.isWritable() && key.isValid()) {
                        write(key);
                    } else if (key.isConnectable()) {
                        System.out.println("连接成功！");
                    }
                }
            }
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    protected void accept(SelectionKey key) throws IOException {
        ServerSocketChannel serverChannel = (ServerSocketChannel) key.channel();
        SocketChannel clientChannel = serverChannel.accept();
        clientChannel.configureBlocking(false);
        clientChannel.register(key.selector(), SelectionKey.OP_READ);
    }

    protected void read(SelectionKey key) throws IOException {
        SocketChannel clientChannel = (SocketChannel) key.channel();
        ByteBuffer byteBuffer = ByteBuffer.allocate(readBufferSize);
        ByteBuffer bodyLenBuffer = ByteBuffer.allocate(bodyOffset);

        long bodyLenRead = clientChannel.read(bodyLenBuffer);
        while (bodyLenRead == bodyOffset) {
            //现获取这个包的长度
            int bodyLen = bodyLenBuffer.getInt(0);
            bodyLenBuffer.clear();

            byteBuffer.clear();
            byteBuffer.limit(bodyLen);
            long bytesRead = clientChannel.read(byteBuffer);
            while (bytesRead != bodyLen) {
                bytesRead += clientChannel.read(byteBuffer);
            }

            byte[] dataByte = byteBuffer.array();
            String info = new String(dataByte).trim();
            System.out.println("从客户端发送过来的消息是：" + info);

            //to do invoke
            Data data = JSON.parseObject(info, Data.class);
            String res = invoke(data.getCmd(), data.getData());
            if (res == null) {
                res = "0";
            }

            Response response = new Response();
            response.setCode(200);
            response.setData(res);
            res = JSON.toJSONString(response);

            byte[] resByte = SocketUtil.strToByteArray(res);

            if (resByte.length + bodyOffset > packageMaxLength) {
                throw new IOException("包的大小超过最大限制");
            }

            ByteBuffer resByteBuffer = ByteBuffer.allocate(resByte.length + bodyOffset + 1);
            resByteBuffer.putInt(resByte.length);
            resByteBuffer.put(resByte);

            clientChannel.register(key.selector(), SelectionKey.OP_WRITE, resByteBuffer);

            break;
        }
    }

    public void write(SelectionKey key) throws IOException {
        ByteBuffer byteBuffer = (ByteBuffer) key.attachment();
        byteBuffer.flip();

        SocketChannel clientChannel = (SocketChannel) key.channel();
        clientChannel.write(byteBuffer);

        byteBuffer.compact();
        clientChannel.close();
    }

    public String invoke(String cmd, String data) {
        if (cmd == null) {
            return null;
        }
        String[] serviceAndMethod = cmd.split("::");

        String className = "Service.Impl." + SocketUtil.uCfirst(serviceAndMethod[0]) + "ServiceImpl";
        String methodName = serviceAndMethod[1];
        Class service = null;
        Method m = null;

        try {
            service = Class.forName(className);
            Method[] methods = service.getMethods();
            for (int i = 0; i < methods.length; i++) {
                if (methodName.equals(methods[i].getName())) {
                    Object servobj = service.newInstance();
                    String[] parameterNames = MethodReflectUtil.getMethodParameterNamesByAnnotation(methods[i]);

                    if (parameterNames.length > 0) {
                        JSONObject jsonObj = JSON.parseObject(data);
                        Object[] args = new Object[parameterNames.length];
                        for (int j = 0; j < parameterNames.length; j++) {
                            if (!jsonObj.containsKey(parameterNames[j])) {
                                return null;
                            }

                            args[j] = jsonObj.get(parameterNames[j]);
                        }

                        return methods[i].invoke(servobj, args).toString();
                    } else {
                        return methods[i].invoke(servobj).toString();
                    }
                }
            }
        } catch (Exception e) {
            return null;
        }

        return null;
    }

    protected void loadConf()
    {
        Config conf = (Config) ctx.singleton("config");
        setPort(conf);
        setBodyOffset(conf);
        setReadBufferSize(conf);
        setWriteBufferSize(conf);
    }

    private void setPort(Config conf) {
        try {
            port = conf.getInt("port");
        } catch (ConfigException e) {}
    }

    private void setBodyOffset(Config conf) {
        try {
            bodyOffset = conf.getInt("package_body_offset");
        } catch (ConfigException e) {}
    }

    private void setReadBufferSize(Config conf) {
        try {
            readBufferSize = conf.getInt("read_buffer_size");
        } catch (ConfigException e) {}
    }

    private void setWriteBufferSize(Config conf) {
        try {
            writebuffSize = conf.getInt("write_buffer_size");
        } catch (ConfigException e) {}
    }
}
