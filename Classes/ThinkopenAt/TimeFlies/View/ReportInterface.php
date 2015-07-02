<?php
namespace ThinkopenAt\TimeFlies\View;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "ThinkopenAt.TimeFlies". *
 *                                                                        *
 *                                                                        */

/*
 * Interface which must get implemented by any class being responsible
 * for generating a report (CSV, ODS, XML, etc.)
 */
interface ReportInterface {

	/*
 	 * Returns the file extension which is used for these report files
 	 *
 	 * @return string The file name suffix
 	 */
	public function getFileExtension();

	/*
 	 * Returns the content type which will be sent in the HTTP header
 	 * for these generated reports
 	 *
 	 * @return string The HTTP Content-Type like "text/csv", "application/pdf"
 	 */
	public function getContentType();

	/*
 	 * Returns the format key which is used for this type of report
 	 *
 	 * @return string The format key like "csv", "pdf", etc.
 	 */
	public function getFormatKey();

	/*
 	 * Returns the name of the report generate class
 	 *
 	 * @return string The name of the report class
 	 */
	public function getName();

	/*
 	 * Returns a description for the report generate class
 	 *
 	 * @return string A textual description of the report type
 	 */
	public function getDescription();

}

