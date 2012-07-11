<?php
/**
 * Copyright (c) Enalean, 2012. All Rights Reserved.
 *
 * This file is a part of Tuleap.
 *
 * Tuleap is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * Tuleap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Tuleap. If not, see <http://www.gnu.org/licenses/>.
 */

require_once CARDWALL_BASE_DIR .'/OnTop/Config/Command.class.php';
require_once CARDWALL_BASE_DIR .'/OnTop/ColumnDao.class.php';

/**
 * Delete a column for a cardwall on top of a tracker
 */
class Cardwall_OnTop_Config_Command_DeleteColumns extends Cardwall_OnTop_Config_Command {

    /**
     * @var Cardwall_OnTop_ColumnDao
     */
    private $dao;

    /**
     * @var Cardwall_OnTop_ColumnMappingFieldValueDao
     */
    private $value_dao;

    public function __construct(
        Tracker $tracker,
        Cardwall_OnTop_ColumnDao $dao,
        Cardwall_OnTop_ColumnMappingFieldValueDao $value_dao
    ) {
        parent::__construct($tracker);
        $this->dao       = $dao;
        $this->value_dao = $value_dao;
    }

    /**
     * @see Cardwall_OnTop_Config_Command::execute()
     */
    public function execute(Codendi_Request $request) {
        if ($request->get('column')) {
            foreach ($request->get('column') as $id => $column_definition) {
                if (empty($column_definition['label'])) {
                    $this->value_dao->deleteForColumn($this->tracker->getId(), $id);
                    $this->dao->delete($this->tracker->getId(), $id);
                    $GLOBALS['Response']->addFeedback('info', 'Column removed');
                }
            }
        }
    }
}
?>
