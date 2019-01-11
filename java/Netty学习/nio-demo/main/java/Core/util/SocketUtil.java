package Core.util;

import java.nio.ByteBuffer;
import java.nio.ByteOrder;

public class SocketUtil {
    public static String bytesToHexString(byte[] src) {
        StringBuilder stringBuilder = new StringBuilder("");
        if (src == null || src.length <= 0) {
            return null;
        }
        for (int i = 0; i < src.length; i++) {
            int v = src[i] & 0xFF;
            String hv = Integer.toHexString(v);
            if (hv.length() < 2) {
                stringBuilder.append(0);
            }
            stringBuilder.append(hv);
        }
        return stringBuilder.toString();
    }

    public static String byteArrayToStr(byte[] byteArray) {
        if (byteArray == null) {
            return null;
        }
        String str = new String(byteArray);
        return str;
    }

    public static byte[] strToByteArray(String str) {
        if (str == null) {
            return null;
        }
        byte[] byteArray = str.getBytes();
        return byteArray;
    }

    public static String uCfirst(String str)
    {
        return str.replaceFirst(str.substring(0, 1),str.substring(0, 1).toUpperCase()) ;
    }

    public static byte[] long2Bytes(Long val)
    {
        ByteBuffer buf = ByteBuffer.allocate(4);
        buf.order(ByteOrder.BIG_ENDIAN);
        buf.putLong(val);
        return buf.array();
    }

    public static byte[] int2Byte(int target) {
        byte[] bytes = new byte[4];
        bytes[0] = (byte)(target & 0xff);
        bytes[1] = (byte)((target >> 8) & 0xff);
        bytes[2] = (byte)((target >> 16) & 0xff);
        bytes[3] = (byte)((target >> 24) & 0xff);
        return bytes;
    }
}
