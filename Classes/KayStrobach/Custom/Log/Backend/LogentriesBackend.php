<?php

namespace KayStrobach\Custom\Log\Backend;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Log\Backend\AbstractBackend;
use TYPO3\Flow\Utility\Now;

class LogentriesBackend extends AbstractBackend {

	// Logentries server address for receiving logs
	const LE_ADDRESS = 'tcp://api.logentries.com';
	// Logentries server address for receiving logs via TLS
	const LE_TLS_ADDRESS = 'tls://api.logentries.com';
	// Logentries server port for receiving logs by token
	const LE_PORT = 10000;
	// Logentries server port for receiving logs with TLS by token
	const LE_TLS_PORT = 20000;

	/**
	 * @var resource
	 */
	protected $resource = NULL;

	/**
	 * @var string
	 */
	protected $key;

	/**
	 * @var array
	 */
	protected $severityLabels = array();

	/**
	 * Carries out all actions necessary to prepare the logging backend, such as opening
	 * the log file or opening a database connection.
	 *
	 * @return void
	 * @api
	 */
	public function open() {
		$this->severityLabels = array(
			LOG_EMERG   => 'Emergeny',
			LOG_ALERT   => 'Alert',
			LOG_CRIT    => 'Critical',
			LOG_ERR     => 'Error',
			LOG_WARNING => 'Warning',
			LOG_NOTICE  => 'Notice',
			LOG_INFO    => 'Info',
			LOG_DEBUG   => 'Debug',
		);

		$this->resource = fsockopen(self::LE_TLS_ADDRESS, self::LE_TLS_PORT, $this->errno, $this->errstr, 2);
	}

	/**
	 * Appends the given message along with the additional information into the log.
	 *
	 * @param string $message The message to log
	 * @param integer $severity One of the LOG_* constants
	 * @param mixed $additionalData A variable containing more information about the event to be logged
	 * @param string $packageKey Key of the package triggering the log (determined automatically if not specified)
	 * @param string $className Name of the class triggering the log (determined automatically if not specified)
	 * @param string $methodName Name of the method triggering the log (determined automatically if not specified)
	 * @return void
	 * @api
	 */
	public function append($message, $severity = LOG_INFO, $additionalData = NULL, $packageKey = NULL, $className = NULL, $methodName = NULL) {
		if ($severity > $this->severityThreshold || empty($this->key)) {
			return;
		}

		$severityLabel = (isset($this->severityLabels[$severity])) ? $this->severityLabels[$severity] : 'UNKNOWN';
		$now = new Now();
		$output = array(
			'eventTime' => $now->format(\DateTime::ISO8601),
			'from' => gethostname(),
			'severity' => trim($severityLabel),
			'packageKey' => $packageKey,
			'message' => $message,
			'className' => $className,
			'methodName' => $methodName
		);

		fputs($this->resource, $this->key . ' ' . json_encode($output) . PHP_EOL);
	}

	/**
	 * Carries out all actions necessary to cleanly close the logging backend, such as
	 * closing the log file or disconnecting from a database.
	 *
	 * @return void
	 * @api
	 */
	public function close() {
		if (is_resource($this->resource)){
			fclose($this->resource);
			$this->resource = null;
		}
	}

	/**
	 * @param string $key
	 * @return LogentriesBackend
	 */
	public function setKey($key) {
		$this->key = trim($key);
		return $this;
	}

}