package Service.Impl;

import Core.annotation.Param;
import Service.UserService;

public class UserServiceImpl implements UserService {

    public String getUser(@Param("id") int id, @Param("name") String name) {

        try {
            Thread.currentThread().sleep(2000);
        } catch (InterruptedException e) {
            e.printStackTrace();
        }
        return "user_"+id+"_"+name;
    }
}


