<?php

/**
 * @author Daniel Sampedro Bello <darthdaniel85@gmail.com>
 * @link http://www.pradosoft.com/
 * @copyright Copyright &copy; 2005-2014 PradoSoft
 * @license http://www.pradosoft.com/license/
 * @since 3.3
 * @package Prado\Wsat\pages
 */

namespace Prado\Wsat\pages;

use Exception;
use Prado\Prado;
use Prado\Web\UI\TPage;
use Prado\Web\UI\WebControls\TCheckBox;
use Prado\Wsat\TWsatScaffoldingGenerator;


class TWsatScaffolding extends TPage
{

        public function onInit($param)
        {
                parent::onInit($param);
                $this->startVisual();
        }

        private function startVisual()
        {
                $scf_generator = new TWsatScaffoldingGenerator();
                foreach ($scf_generator->getAllTableNames() as $tableName)
                {
                        $dynChb = new TCheckBox();
                        $dynChb->ID = "cb_$tableName";
                        $dynChb->Text = ucfirst($tableName);
                        $dynChb->Checked = true;
                        $this->registerObject("cb_$tableName", $dynChb);
                        $this->tableNames->getControls()->add($dynChb);
                        $this->tableNames->getControls()->add("</br>");
                }
        }

        public function generate($sender)
        {
                if ($this->IsValid)
                {
                        try
                        {
                                $scf_generator = new TWsatScaffoldingGenerator();
                                $scf_generator->setOpFile($this->output_folder->Text);
                                $scf_generator->generate();
                                $this->feedback_panel->CssClass = "green_panel";
                                $this->generation_msg->Text = "The code has been generated successfully.";
                        } catch (Exception $ex)
                        {
                                $this->feedback_panel->CssClass = "red_panel";
                                $this->generation_msg->Text = $ex->getMessage();
                        }
                        $this->feedback_panel->Visible = true;
                }

                //   $scf_generator->renderAllTablesInformation();
        }
}