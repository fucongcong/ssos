```java
EventLoopGroup bossGroup = new NioEventLoopGroup();
EventLoopGroup workerGroup = new NioEventLoopGroup();
//初始化EventLoop，默认启动 cpu*2 的线程池
//cpu*2 的线程 注册nio SingleThreadEventLoop 单线程eventloop
try {
    ServerBootstrap b = new ServerBootstrap();
    b.group(bossGroup, workerGroup)
            .channel(NioServerSocketChannel.class)
            .childHandler(new ChannelInitializer<SocketChannel>() {
                @Override
                public void initChannel(SocketChannel ch) throws Exception {
                    ch.pipeline().addLast(new MsgDecoder(), new MsgEncoder(), new CoServerHandler());
                }
            })
            .option(ChannelOption.SO_BACKLOG, 128)
            .childOption(ChannelOption.SO_KEEPALIVE, true);

    // Bind and start to accept incoming connections.
    ChannelFuture f = b.bind(port).sync();

    // Wait until the server socket is closed.
    // In this example, this does not happen, but you can do that to gracefully
    // shut down your server.
    f.channel().closeFuture().sync();
} catch (InterruptedException e) {
    e.printStackTrace();
} finally {
    workerGroup.shutdownGracefully();
    bossGroup.shutdownGracefully();
}
```

#### 注册eventloop事件
ServerBootstrap是netty的主类。
bossGroup理解为是主进程的eventloop
workerGroup理解为是子进程(工作进程)的eventloop

```java
    ServerBootstrap b = new ServerBootstrap();
    b.group(bossGroup, workerGroup)
    .channel(NioServerSocketChannel.class) // 设置管道通信类型 
    .childHandler(new ChannelInitializer<SocketChannel>() {//设置管道适配器
        @Override
        public void initChannel(SocketChannel ch) throws Exception {
            //设置管道中的 出站入站的数据处理器。都是继承与ChannelInboundHandlerAdapter，ChannelOutboundHandlerAdapter 这两个类。处理有先后顺序之分。
            ch.pipeline().addLast(new MsgDecoder(), new MsgEncoder(), new CoServerHandler());
        }
    })
    //设置一些主进程参数
    .option(ChannelOption.SO_BACKLOG, 128)
    //设置一些子进程参数
    .childOption(ChannelOption.SO_KEEPALIVE, true);
```


