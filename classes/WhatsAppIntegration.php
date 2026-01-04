<?php

class WhatsAppIntegration {
    private $groupInviteLink;
    private $config;
    
    public function __construct() {
        $this->config = require __DIR__ . '/../config/whatsapp_config.php';
        $this->groupInviteLink = $this->config['group_invite_link'];
    }
    
    public function envoyerInvitationGroupe($telephone, $nom, $prenom) {
        $telephone = $this->formaterNumeroTelephone($telephone);
        
        if (!$telephone) {
            return false;
        }
        
        $messageTemplate = $this->config['invitation_message_template'];
        $messageText = str_replace(
            ['{prenom}', '{nom}', '{group_link}'],
            [$prenom, $nom, $this->groupInviteLink],
            $messageTemplate
        );
        
        $message = urlencode($messageText);
        
        $whatsappUrl = "https://wa.me/$telephone?text=$message";
        
        if ($this->config['log_invitations']) {
            $this->logInvitation($telephone, $nom, $prenom, $whatsappUrl);
        }
        
        return [
            'success' => true,
            'url' => $whatsappUrl,
            'telephone' => $telephone,
            'message' => "Invitation WhatsApp préparée pour $prenom $nom"
        ];
    }
    
    private function formaterNumeroTelephone($telephone) {
        $telephone = preg_replace('/[^0-9]/', '', $telephone);
        
        if (strlen($telephone) == 9 && substr($telephone, 0, 1) == '7') {
            $telephone = '221' . $telephone;
        } elseif (strlen($telephone) == 10 && substr($telephone, 0, 1) == '0') {
            $telephone = '221' . substr($telephone, 1);
        } elseif (strlen($telephone) == 12 && substr($telephone, 0, 3) == '221') {
        } else {
            return false;
        }
        
        return $telephone;
    }
    
    private function logInvitation($telephone, $nom, $prenom, $url) {
        $logDir = __DIR__ . '/../logs';
        if (!file_exists($logDir)) {
            mkdir($logDir, 0777, true);
        }
        
        $logFile = $logDir . '/whatsapp_invitations.log';
        $logEntry = date('Y-m-d H:i:s') . " - Invitation envoyée à $prenom $nom ($telephone)\n";
        $logEntry .= "URL: $url\n";
        $logEntry .= str_repeat('-', 80) . "\n";
        
        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }
    
    public function genererLienInvitationDirecte($telephone) {
        $telephone = $this->formaterNumeroTelephone($telephone);
        
        if (!$telephone) {
            return false;
        }
        
        $message = urlencode("Cliquez sur ce lien pour rejoindre notre groupe WhatsApp :\n$this->groupInviteLink");
        
        return "https://wa.me/$telephone?text=$message";
    }
    
    public function envoyerInvitationAutomatique($telephone, $nom, $prenom) {
        $result = $this->envoyerInvitationGroupe($telephone, $nom, $prenom);
        
        if ($result && isset($result['url'])) {
            $this->ouvrirWhatsAppWeb($result['url']);
        }
        
        return $result;
    }
    
    private function ouvrirWhatsAppWeb($url) {
    }
}
?>
