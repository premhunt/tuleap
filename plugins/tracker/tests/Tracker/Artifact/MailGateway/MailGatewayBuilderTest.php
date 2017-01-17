<?php
/**
 * Copyright (c) Enalean, 2015. All Rights Reserved.
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

require_once TRACKER_BASE_DIR . '/../tests/bootstrap.php';

class Tracker_Artifact_MailGateway_MailGatewayBuilderTest extends TuleapTestCase {

    /** @var Tracker_Artifact_MailGateway_MailGatewayBuilder */
    private $mailgateway_builder;

    private $insecure_raw_mail;
    private $token_raw_mail;

    public function setUp() {
        parent::setUp();
        $fixtures = dirname(__FILE__). '/_fixtures';

        $this->insecure_raw_mail = file_get_contents($fixtures .'/insecure-reply-comment.plain.eml');
        $this->token_raw_mail    = file_get_contents($fixtures .'/reply-comment.plain.eml');

        $incoming_message_factory = mock('Tracker_Artifact_MailGateway_IncomingMessageFactory');
        $artifact_factory         = mock('Tracker_ArtifactFactory');
        $parser                   = new Tracker_Artifact_MailGateway_Parser();
        $tracker_artifactbyemail  = mock('Tracker_ArtifactByEmailStatus');
        $logger                   = mock('Logger');
        $notifier                 = mock('Tracker_Artifact_MailGateway_Notifier');
        $incoming_mail_dao        = mock('Tracker_Artifact_Changeset_IncomingMailDao');
        $citation_stripper        = mock('Tracker_Artifact_MailGateway_CitationStripper');

        $this->mailgateway_builder = new Tracker_Artifact_MailGateway_MailGatewayBuilder(
            $parser,
            $incoming_message_factory,
            $citation_stripper,
            $notifier,
            $incoming_mail_dao,
            $artifact_factory,
            $tracker_artifactbyemail,
            $logger,
            mock('Tuleap\Tracker\Artifact\MailGateway\MailGatewayFilter')
        );
    }

    public function itReturnsAnInsecureMailGateway() {
        $mailgateway = $this->mailgateway_builder->build($this->insecure_raw_mail);

        $this->assertIsA($mailgateway, 'Tracker_Artifact_MailGateway_InsecureMailGateway');
    }

    public function itReturnsATokenMailGateway() {
        $mailgateway = $this->mailgateway_builder->build($this->token_raw_mail);

        $this->assertIsA($mailgateway, 'Tracker_Artifact_MailGateway_TokenMailGateway');
    }
}
