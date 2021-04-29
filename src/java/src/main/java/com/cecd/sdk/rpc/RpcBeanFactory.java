package com.cecd.sdk.rpc;

import lombok.extern.slf4j.Slf4j;
import org.springframework.beans.BeansException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.ApplicationContext;
import org.springframework.context.ApplicationContextAware;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Component;

@Slf4j
@Component
public class RpcBeanFactory implements ApplicationContextAware {

    private ApplicationContext applicationContext;

    @Autowired
    private Environment environment;

    @Override
    public void setApplicationContext(ApplicationContext applicationContext) throws BeansException {
        log.info("rpc init RpcBeanFactory");
        this.applicationContext=applicationContext;
        RpcFactory.setEnvironment(environment);
    }

    public ApplicationContext getApplicationContext() {
        return applicationContext;
    }
}
