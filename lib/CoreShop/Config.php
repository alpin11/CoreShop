<?php

class CoreShop_Config {

    /**
     * @static
     * @return Zend_Config_Xml
     */
    public static function getModelClassMappingConfig () {

        $config = null;

        if(Zend_Registry::isRegistered("coreshop_config_model_classmapping")) {
            $config = Zend_Registry::get("coreshop_config_model_classmapping");
        } else {
            $mappingFile = PIMCORE_CONFIGURATION_DIRECTORY . "/coreshop_classmap.xml";

            if(is_file($mappingFile) && is_readable($mappingFile)) {
                try {
                    $config = new Zend_Config_Xml($mappingFile);
                    self::setModelClassMappingConfig($config);
                } catch (Exception $e) {
                    Logger::error("coreshop_classmap.xml exists but it is not a valid Zend_Config_Xml configuration. Maybe there is a syntaxerror in the XML.");
                }
            }
        }
        return $config;
    }

    /**
     * @static
     * @param Zend_Config $config
     * @return void
     */
    public static function setModelClassMappingConfig (Zend_Config $config) {
        Zend_Registry::set("coreshop_config_model_classmapping", $config);
    }
}
