package Core;

import java.util.Hashtable;
import java.util.Map;

public class Context {

    protected Map<String, Object> instances = new Hashtable<>();

    public Object singleton(String name){
        return singleton(name, null);
    }

    public Object singleton(String name, Object obj){
        if (instances.get(name) != null) {
            return instances.get(name);
        }

        if (obj != null) {
            instances.put(name, obj);
        }

        return obj;
    }

}
