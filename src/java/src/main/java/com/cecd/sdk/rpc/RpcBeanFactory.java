package com.cecd.sdk.rpc;

import org.springframework.beans.BeansException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.ApplicationContext;
import org.springframework.context.ApplicationContextAware;
import org.springframework.core.env.Environment;
import org.springframework.stereotype.Component;

@Component
public class RpcBeanFactory implements ApplicationContextAware {

    private ApplicationContext applicationContext;

    @Autowired
    private Environment environment;

    @Override
    public void setApplicationContext(ApplicationContext applicationContext) throws BeansException {
        this.applicationContext=applicationContext;
        RpcFactory.setEnvironment(environment);
    }

    public ApplicationContext getApplicationContext() {
        return applicationContext;
    }
}
