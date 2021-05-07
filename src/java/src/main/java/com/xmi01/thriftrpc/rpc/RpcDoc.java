package com.xmi01.thriftrpc.rpc;

import java.lang.annotation.*;

/**
 * 定义权限注解
 */
@Target({ElementType.TYPE, ElementType.METHOD})
@Retention(RetentionPolicy.RUNTIME)
@Inherited
@Documented
public @interface RpcDoc {
    String value();
}
