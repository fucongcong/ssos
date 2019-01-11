import Service.UserService;
import com.typesafe.config.Config;
import Core.Context;
import Core.NioServer;
import com.typesafe.config.ConfigFactory;

public class RpcServer {

    public static void main(String[] args) {
        Config configLoader = ConfigFactory.load();
        Context ctx = new Context();
        ctx.singleton("config", configLoader);

        NioServer server = new NioServer(ctx);
        server.run();
    }
}