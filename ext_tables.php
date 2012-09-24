<?php
if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$extconf = unserialize($TYPO3_CONF_VARS['EXT']['extConf'][$_EXTKEY]);


$TCA['tx_tweetthis_tweets'] = array (
	'ctrl' => array (
		'title'     => 'LLL:EXT:tweet_this/locallang_db.xml:tx_tweetthis_tweets',		
		'label'     => 'text',
		'tstamp'    => 'tstamp',
		'crdate'    => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY crdate',	
		'delete' => 'deleted',	
		'dynamicConfigFile' => t3lib_extMgm::extPath($_EXTKEY).'tca.php',
		'iconfile'          => t3lib_extMgm::extRelPath($_EXTKEY).'icon_tx_tweetthis_tweets.gif',
	),
);

$tempColumns = array (
    'tx_tweetthis_signature' => array (
        'exclude' => 0,
        'label' => 'LLL:EXT:tweet_this/locallang_db.xml:be_users.tx_tweetthis_signature',
        'config' => array (
            'type' => 'input',
            'size' => '30',
        )
    ),
);


t3lib_div::loadTCA('be_users');
t3lib_extMgm::addTCAcolumns('be_users',$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes('be_users','tx_tweetthis_signature', '', 'after:email');


$tempColumns = array (
    'tx_tweetthis_tweetthis' => array (
        'exclude' => 0,
        'label' => 'LLL:EXT:tweet_this/locallang_db.xml:tx_tweetthis_tweetthis',
        'config' => array (
            'type' => 'user',
	    'userFunc' => 'EXT:tweet_this/classes/class.tx_tweetthis_userField.php:&tx_tweetthis_userField->renderFieldTweetThis',
	    'tweetthis_title' => 'title'
        )
    ),
);

if (strpos($extconf['enableFor'], 'pages') !== FALSE) {
	t3lib_div::loadTCA('pages');
	t3lib_extMgm::addTCAcolumns('pages',$tempColumns,1);
	t3lib_extMgm::addToAllTCAtypes('pages', 'tx_tweetthis_tweetthis;;;;1-1-1');
}

if (strpos($extconf['enableFor'], 'tt_news') !== FALSE && t3lib_extMgm::isLoaded('tt_news')) {
	t3lib_div::loadTCA('tt_news');
	t3lib_extMgm::addTCAcolumns('tt_news',$tempColumns,1);
	t3lib_extMgm::addToAllTCAtypes('tt_news', 'tx_tweetthis_tweetthis;;;;1-1-1');
}

if (strpos($extconf['enableFor'], 't3blog') !== FALSE && t3lib_extMgm::isLoaded('t3blog')) {
	t3lib_div::loadTCA('tx_t3blog_post');
	t3lib_extMgm::addTCAcolumns('tx_t3blog_post',$tempColumns,1);
	t3lib_extMgm::addToAllTCAtypes('tx_t3blog_post', 'tx_tweetthis_tweetthis;;;;1-1-1', '', 'after:trackback');
}

if (strpos($extconf['enableFor'], 'tt_content') !== FALSE) {
	t3lib_div::loadTCA('tt_content');
	t3lib_extMgm::addTCAcolumns('tt_content', array(
		'tx_tweetthis_tweetthis' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:tweet_this/locallang_db.xml:tx_tweetthis_tweetthis',
			'config' => array(
				'type' => 'user',
				'userFunc' => 'EXT:tweet_this/classes/class.tx_tweetthis_userField.php:&tx_tweetthis_userField->renderFieldTweetThis',
				'tweetthis_title' => 'header'
			)
		),
	), 1);
	t3lib_extMgm::addToAllTCAtypes('tt_content', 'tx_tweetthis_tweetthis;;;;1-1-1');
}

if (strpos($extconf['enableFor'], 'kb_eventboard') !== FALSE && t3lib_extMgm::isLoaded('kb_eventboard')) {
	t3lib_div::loadTCA('tx_kbeventboard_events');
	t3lib_extMgm::addTCAcolumns('tx_kbeventboard_events', array(
		'tx_tweetthis_tweetthis' => array(
			'exclude' => 0,
			'label' => 'LLL:EXT:tweet_this/locallang_db.xml:tx_tweetthis_tweetthis',
			'config' => array(
				'type' => 'user',
				'userFunc' => 'EXT:tweet_this/classes/class.tx_tweetthis_userField.php:&tx_tweetthis_userField->renderFieldTweetThis',
				'tweetthis_title' => 'eventname'
			)
		),
	), 1);
	t3lib_extMgm::addToAllTCAtypes('tx_kbeventboard_events', 'tx_tweetthis_tweetthis;;;;1-1-1');
}

$TYPO3_CONF_VARS['BE']['AJAX']['tx_tweetthis::sendTweet'] = 'EXT:tweet_this/classes/class.tx_tweetthis_AjaxHandler.php:tx_tweetthis_AjaxHandler->sendTweet';
t3lib_extMgm::addStaticFile($_EXTKEY,'static/', 'tweet this');

?>