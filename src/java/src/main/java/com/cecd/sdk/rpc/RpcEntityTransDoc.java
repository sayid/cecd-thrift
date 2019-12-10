package com.cecd.sdk.rpc;

import org.springframework.core.annotation.AliasFor;

import java.lang.annotation.*;

/**
 * 定义实体对象是否要转换驼峰 或者下划线
 */
@Target({ElementType.TYPE})
@Retention(RetentionPolicy.RUNTIME)
@Inherited
@Documented
public @interface RpcEntityTransDoc {
    @AliasFor("type")
    String type() default "LOWER_CAMEL_CASE";//SNAKE_CASE
}
