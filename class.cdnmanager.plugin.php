<?php if (!defined('APPLICATION')) exit();



$PluginInfo['CDNManager'] = array(
	'Author' => 'xjtdy888',
	'AuthorUrl' => 'http://github.com/xjtdy888',
    'Name' => 'CDNManager',
    'Description' => '用CDN加速您的站点',
    'Version' => '1.0.1',
    'MobileFriendly' => TRUE,
    'RequiredApplications' => array('Vanilla' => '2.1'),
    'RequiredTheme' => FALSE,
    'RequiredPlugins' => FALSE,
    'SettingsUrl' => '/settings/cdnmanager',
   'SettingsPermission' => 'Garden.Settings.Manage'
);

class CDNManagerPlugin extends Gdn_Plugin {
    public function Base_AfterJsCdns_Handler($Sender, $args) {
		$Cdns = &$args['Cdns'];

        $cdnvalue = c('Plugins.CDNManager.CDNSources');

        if ($cdnvalue == "") {
            return ;
        }
        $value = parse_ini_string ($cdnvalue);
        $Cdns = array_merge($Cdns, $value);
    }


    public function settingsController_cdnmanager_create($Sender, $args) {
        $Sender->permission('Garden.Settings.Manage');
        $Cf = new ConfigurationModule($Sender);

        if (c('Plugins.CDNManager.CDNSources') == "") {
            $defaultCDNValue = "jquery.js = \"http://libs.baidu.com/jquery/1.10.2/jquery.min.js\"\r\njquery-ui.js = \"http://apps.bdimg.com/libs/jqueryui/1.10.4/jquery-ui.min.js\"";
            Gdn::config()->set('Plugins.CDNManager.CDNSources', $defaultCDNValue, false, false);
        }

        $Cf->initialize(array(
            'Plugins.CDNManager.CDNSources' => array('LabelCode' => 'CDN使用源列表', 'Control' => 'TextBox', 'Options'=>array('MultiLine'=>true, 'rows'=>20,'cols'=>50), 'Description' => '<p>输入需要用CDN加速的文件和对应的地址，如jquery等国内国外都有开放的CDN源，也可申请<font color="red"><a href="https://portal.qiniu.com/signup?code=3lcqpvqtedfma" target="_blank">七牛</a></font>免费的空间来加速您的网站,<a href="https://portal.qiniu.com/signup?code=3lcqpvqtedfma" target="_blank">点击这里免费申请七牛加速空间</a></p> <p><small><strong>
</strong> </small></p>', 'Items' => array())
        ));

        $c = Gdn::controller();
        $c->addJsFile('settings.js', 'plugins/CDNManager');

        $Sender->addSideMenu();
        $Sender->setData('Title', t('CDN源设置'));
        $Cf->renderAll();
    }

}
