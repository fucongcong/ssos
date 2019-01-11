package Core;

import java.io.BufferedInputStream;
import java.io.DataOutputStream;
import java.io.IOException;
import java.lang.reflect.Method;
import java.net.InetSocketAddress;
import java.net.ServerSocket;
import java.net.Socket;
import java.nio.channels.SelectionKey;
import java.nio.channels.Selector;
import java.nio.channels.ServerSocketChannel;
import java.util.Iterator;
import java.util.Set;

import Core.pack.Data;
import Core.util.MethodReflectUtil;
import Core.util.SocketUtil;
import com.alibaba.fastjson.JSON;
import com.alibaba.fastjson.JSONObject;

public class Server {

    private Selector selector;

    protected ServerSocket server;

    protected int port = 9394;

    protected long packageMaxLength = 2000000;

    public Server() {}

    public Server(Integer port) {
        this.port = port;
    }

    public void run() {
        try {
            server = new ServerSocket(port);

            //while (true) {
                try {
                    Socket client = server.accept();
                    Data data = read(client);
                    String res = invoke(data.getCmd(), data.getData());
                    write(data.getCmd(), res, client);
                    closeClient(client);
                } catch (IOException e) {
                    e.printStackTrace();
                }
            //}
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    public void write(String cmd, String res, Socket client) throws IOException {
        if (res == null) {
            res = "0";
        }

        Data response = new Data();
        response.setCmd(cmd);
        response.setData(res);
        res = JSON.toJSONString(response);
        DataOutputStream out = new DataOutputStream(client.getOutputStream());
        out.writeInt(res.length());
        out.writeBytes(res);
    }

    public void closeClient(Socket client) throws IOException {
        client.shutdownInput();
        client.close();
    }

    public void close() throws IOException {
        server.close();
    }

    public Data read(Socket client) throws IOException {
        BufferedInputStream is = new BufferedInputStream(client.getInputStream());
        byte[] buf = new byte[4];
        is.read(buf, 0, 4);
        int bodylEN = Integer.parseInt(SocketUtil.bytesToHexString(buf), 16);
        if (bodylEN <= 0 || bodylEN > packageMaxLength) {
            throw new IOException();
        }

        byte[] body = new byte[bodylEN];
        is.read(body, 0, bodylEN);

        String bodyData = SocketUtil.byteArrayToStr(body);
        return JSON.parseObject(bodyData, Data.class);
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
                        return methods[i].invoke(servobj, null).toString();
                    }
                }
            }
        } catch (Exception e) {
            return null;
        }

        return null;
    }
}
