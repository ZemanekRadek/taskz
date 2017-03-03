<?php

namespace DK\NetteTranslator;

use Nette\Object;
use Nette\Diagnostics\IBarPanel;
use Nette\Diagnostics\Debugger;
use Nette\Application\Application;
use Nette\Http\Request;
use Nette\Http\Response;

/**
 *
 * @author David Kudera
 */
class Panel extends Object implements IBarPanel
{


	const XHR_HEADER = 'X-Translator-Panel';


	const AJAX_ACTION_KEY = 'action';

	const AJAX_MESSAGE_KEY = 'message';

	const AJAX_TRANSLATION_KEY = 'translation';


	const AJAX_ACTION_LOAD = 'load';

	const AJAX_ACTION_EDIT = 'edit';


	const COOKIE_DEBUG_KEY = 'dk-translator-debug';


	/** @var \Nette\Application\Application  */
	private $application;

	/** @var \DK\NetteTranslator\Translator  */
	private $translator;

	/** @var array */
	private $groups = array();

	/** @var \Nette\Http\Request  */
	private $httpRequest;

	/** @var \Nette\Http\Response  */
	private $httpResponse;


	/**
	 * @param \Nette\Application\Application $application
	 * @param \DK\NetteTranslator\Translator $translator
	 * @param \Nette\Http\Request $httpRequest
	 * @param \Nette\Http\Response $httpResponse
	 */
	public function __construct(Application $application, Translator $translator, Request $httpRequest, Response $httpResponse)
	{
		$this->translator = $translator;
		$this->application = $application;
		$this->httpRequest = $httpRequest;
		$this->httpResponse = $httpResponse;

		$this->processRequest();
	}


	/**
	 * @param string $name
	 * @param string $pattern
	 * @return \DK\NetteTranslator\Panel
	 */
	public function addGroup($name, $pattern)
	{
		$this->groups[$name] = $pattern;
		return $this;
	}


	private function processRequest()
	{
		if ($this->httpRequest->isPost() && $this->httpRequest->isAjax() && $this->httpRequest->getHeader(self::XHR_HEADER)) {
			$data = json_decode(file_get_contents('php://input'), true);

			if ($data && isset($data[self::AJAX_ACTION_KEY])) {
				switch ($data[self::AJAX_ACTION_KEY]) {
					case self::AJAX_ACTION_LOAD:
						$message = $data[self::AJAX_MESSAGE_KEY];

						if (!$this->translator->hasTranslation($message)) {
							throw new \Exception;
						}

						$data = array(
							'translation' => $this->translator->findTranslation($message),
						);

						$this->httpResponse->setContentType('application/json');

						echo json_encode($data);
					break;
					case self::AJAX_ACTION_EDIT:
						$message = $data[self::AJAX_MESSAGE_KEY];
						$translation = $data[self::AJAX_TRANSLATION_KEY];

						if (!$this->translator->hasTranslation($message)) {
							throw new \Exception;
						}

						$info = $this->translator->getMessageInfo($message);
						$data = $this->translator->_loadCategory($info['path'], $info['category']);

						$data[$info['name']] = $translation;

						$this->translator->getLoader()->save($info['path'], $info['category'], $this->translator->getLanguage(), $data);

						$this->httpResponse->setContentType('application/json');
					break;
					default:
						throw new \Exception;
					break;
				}
			}

			exit;
		}
	}


	/**
	 * @return string
	 */
	public function getTab()
	{
		return '<span title="Translation"><img width="16px" height="16px" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAB8klEQVQ4T62STU8TURiFz52ZTjvTKdfuyo6gLlxopVLSSCQ00H9gUATjxmhiYkJi/EGuG5PuGmICTbFAKFM61qgsTPzOAP3utON0OtRrF01GNpT4bu7qPPec9z1keWUp2XNwt3kaxKExgVGH3H+w1IrH40r27TauJNYgK35UG20c1Qwc11qoNjsgnABC+MHr94kISF4EZBGZ9U2Q5ZV79UQiQTcyW5hcfA6/ovw/AE8cSB4OiuRDxejis96BwPNnHSwuLNDNbG7ooO/YkEWCWssE+qeQmW2O4/G9bILKXrz/1nRHiM/P02xudwgIShy6PRtGx4KPOThpWhAFAabdx2RoDOsF3Q2Ym7tDczv5IWDMS+A4PRimNXAhejwshohyy8ZlBnhTPHIDZmdv0929whBgmr9xdVwZiH9VWggGZOaoj64D2Ow9/Gm4AbFYjObVousKEtvB+CUvvugNWExE/T4ILMb2pzKL5XEDZqJRqhZLZ85oWiw7D9TavcHmf1QtSCIT/9uDSGSKaqWPF+9BOHyDHmgfsF+/BqfPvhxhBk38C9C0dw2zbU2kUqn6CHqQh49Wv96cCjvFA40vH1eup9Pp5kiAp8+evI7eiuh5tRCqnpRWk8kdVr/zD3nxcu1xdGZaz+/thzIbW69UVbXPLwf+AELaHGAIHFYOAAAAAElFTkSuQmCC">'
			. ' '. $this->translator->getLanguage(). (count($this->translator->getUntranslated()) > 0 ? ' <b>('. count($this->translator->getUntranslated()). ' untranslated)</b>' : '')
			.'</span>';
	}


	/**
	 * @return string
	 */
	public function getPanel()
	{
		$groups = array();
		$empty = true;

		$translated = $this->translator->getTranslated();

		foreach ($this->groups as $name => $pattern) {
			$group = array();
			$translated = array_filter($translated, function($translation) use (&$group, $pattern) {
				if (preg_match('/'. $pattern. '/', $translation)) {
					$group[] = $translation;
					return false;
				} else {
					return true;
				}
			});

			if ($empty && !empty($group)) {
				$empty = false;
			}

			$groups[$name] = array(
				'collapsed' => true,
				'data' => $group,
			);
		}

		$groups['Untranslated'] = array(
			'collapsed' => false,
			'data' => $this->translator->getUntranslated(),
		);

		$groups['Translated'] = array(
			'collapsed' => false,
			'data' => $translated,
		);

		if (count($groups['Untranslated']['data']) === 0 && count($groups['Translated']['data']) === 0 && $empty) {
			return null;
		}

		$translator = $this->translator;

		$link = $this->application->getPresenter()->link('this');

		$xhrHeader = self::XHR_HEADER;

		$ajaxActionKey = self::AJAX_ACTION_KEY;
		$ajaxMessageKey = self::AJAX_MESSAGE_KEY;
		$ajaxTranslationKey = self::AJAX_TRANSLATION_KEY;

		$ajaxActionLoad = self::AJAX_ACTION_LOAD;
		$ajaxActionEdit = self::AJAX_ACTION_EDIT;

		$cookieDebugKey = self::COOKIE_DEBUG_KEY;

		$checkMessage = function($message) {
			return preg_match('/^[a-zA-Z0-9._]+$/', $message) > 0;
		};

		ob_start();
		require __DIR__. '/panel.phtml';
		return ob_get_clean();
	}


	public function register()
	{
		Debugger::addPanel($this, 'dk.translator');
	}

}
 