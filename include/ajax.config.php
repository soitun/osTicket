<?php
/*********************************************************************
    ajax.content.php

    AJAX interface for content fetching...allowed methods.

    Peter Rotich <peter@osticket.com>
    Copyright (c)  2006-2013 osTicket
    http://www.osticket.com

    Released under the GNU General Public License WITHOUT ANY WARRANTY.
    See LICENSE.TXT for details.

    vim: expandtab sw=4 ts=4 sts=4:
**********************************************************************/

if(!defined('INCLUDE_DIR')) die('!');

class ConfigAjaxAPI extends AjaxController {

    //config info UI might need.
    function scp() {
        global $cfg;

        $config=array(
              'lock_time'       => ($cfg->getLockTime()*3600),
              'max_file_uploads'=> (int) $cfg->getStaffMaxFileUploads(),
              'html_thread'     => (bool) $cfg->isHtmlThreadEnabled(),
              'date_format'     => ($cfg->getDateFormat()),
              'allow_attachments' => (bool) $cfg->allowAttachments(),
        );
        return $this->json_encode($config);
    }

    function client() {
        global $cfg;

        $config=array(
            'allow_attachments' => (bool) $cfg->allowOnlineAttachments(),
            'file_types'      => $cfg->getAllowedFileTypes(),
            'max_file_size'   => (int) $cfg->getMaxFileSize(),
            'max_file_uploads'=> (int) $cfg->getClientMaxFileUploads(),
            'html_thread'     => (bool) $cfg->isHtmlThreadEnabled(),
        );

        $config = $this->json_encode($config);
        Http::cacheable(md5($config), $cfg->lastModified());

        return $config;
    }

    function templateLinks() {
        $links = $this->json_encode(array(
            array('name'=>'Select ...', 'url'=> false),
            array('name'=>'Agent Ticket Link', 'url'=> '%{ticket.staff_link}'),
            array('name'=>'Agent Login Page', 'url'=> '%{url}/scp'),
            array('name'=>'End-User Ticket Link', 'url'=> '%{recipient.ticket_link}'),
            array('name'=>'End-User Login Page', 'url'=> '%{url}/login.php'),
        ));

        Http::cacheable(md5($links), filemtime(__file__));
        header('Content-Type: application/json; charset=UTF-8');

        return $links;
    }
}
?>
