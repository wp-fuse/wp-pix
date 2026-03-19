<?php
/**
 * Plugin Name: WP PIX QR Code
 * Description: Plugin para gerar QR codes PIX com shortcode e bloco Gutenberg
 * Version: 1.0.0
 * Author: wpfuse
 * Text Domain: wpfuse
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_PIX_QRCode {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_block_editor_assets'));
        add_action('wp_ajax_wp_pix_generate_qr', array($this, 'ajax_generate_qr'));
        add_action('wp_ajax_nopriv_wp_pix_generate_qr', array($this, 'ajax_generate_qr'));
    }
    
    public function init() {
        // Registra o shortcode
        add_shortcode('pix_qr', array($this, 'shortcode_pix_qr'));
        
        // Registra o bloco Gutenberg
        register_block_type('wp-pix/qr-code', array(
            'render_callback' => array($this, 'render_block'),
            'attributes' => array(
                'chave' => array('type' => 'string', 'default' => ''),
                'valor' => array('type' => 'string', 'default' => ''),
                'identificador' => array('type' => 'string', 'default' => ''),
                'size' => array('type' => 'string', 'default' => '200'),
                'align' => array('type' => 'string', 'default' => 'center')
            )
        ));
    }
    
    public function enqueue_block_editor_assets() {
        wp_enqueue_script(
            'wp-pix-block',
            plugin_dir_url(__FILE__) . 'wp-pix-block.js',
            array('wp-blocks', 'wp-element', 'wp-components', 'wp-block-editor'), '1.0.0'
        );
        wp_localize_script('wp-pix-block', 'wpPixAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
    }
    
    public function ajax_generate_qr() {
        $chave = sanitize_text_field($_GET['chave'] ?? '');
        $valor = sanitize_text_field($_GET['valor'] ?? '0');
        $identificador = sanitize_text_field($_GET['identificador'] ?? '');
        $size = sanitize_text_field($_GET['size'] ?? '200');
        
        if (empty($chave)) {
            wp_die('Chave PIX é obrigatória');
        }
        
        $qr_url = $this->geraQRCodePix($chave, $valor, '', $identificador, $size);
        echo $qr_url;
        wp_die();
    }
    
    public function shortcode_pix_qr($atts) {
        $atts = shortcode_atts(array(
            'chave' => '',
            'valor' => '0',
            'identificador' => '',
            'size' => '200',
            'align' => 'center'
        ), $atts);
        
        return $this->render_qr_code($atts);
    }
    
    public function render_block($attributes) {
        return $this->render_qr_code($attributes);
    }
    
    private function render_qr_code($args) {
        if (empty($args['chave'])) {
            return '<p style="color: red;">Erro: Chave PIX é obrigatória.</p>';
        }
        
        $valor = !empty($args['valor']) ? $args['valor'] : '0';
        
        $qr_url = $this->geraQRCodePix(
            $args['chave'],
            $valor,
            $args['identificador'],
            $args['size']
        );
        
        $align_style = '';
        if ($args['align'] === 'center') {
            $align_style = 'text-align: center;';
        } elseif ($args['align'] === 'right') {
            $align_style = 'text-align: right;';
        }
        
        return sprintf(
            '<div class="wp-pix-qr-container" style="%s"><img src="%s" alt="QR Code PIX" style="max-width: 100%%; height: auto;" /></div>',
            $align_style,
            esc_url($qr_url)
        );
    }

    private function montaPix($px){
        $ret="";
        foreach ($px as $k => $v) {
            if (!is_array($v)) {
                if ($k == 54) { $v = number_format($v,2,'.',''); } // Formata o campo valor com 2 digitos.
                else { $v = $this->remove_char_especiais($v); }
                $ret .= $this->c2($k).$this->cpm($v).$v;
            }
            else {
                $conteudo = $this->montaPix($v);
                $ret .= $this->c2($k).$this->cpm($conteudo).$conteudo;
            }
        }
        return $ret;
    }

    private function remove_char_especiais($txt){
        return preg_replace('/\W /','', $this->remove_acentos($txt));
    }

    private function remove_acentos($texto){
        $search	 = explode(",","à,á,â,ä,æ,ã,å,ā,ç,ć,č,è,é,ê,ë,ē,ė,ę,î,ï,í,ī,į,ì,ł,ñ,ń,ô,ö,ò,ó,œ,ø,ō,õ,ß,ś,š,û,ü,ù,ú,ū,ÿ,ž,ź,ż,À,Á,Â,Ä,Æ,Ã,Å,Ā,Ç,Ć,Č,È,É,Ê,Ë,Ē,Ė,Ę,Î,Ï,Í,Ī,Į,Ì,Ł,Ñ,Ń,Ô,Ö,Ò,Ó,Œ,Ø,Ō,Õ,Ś,Š,Û,Ü,Ù,Ú,Ū,Ÿ,Ž,Ź,Ż");
        $replace = explode(",","a,a,a,a,a,a,a,a,c,c,c,e,e,e,e,e,e,e,i,i,i,i,i,i,l,n,n,o,o,o,o,o,o,o,o,s,s,s,u,u,u,u,u,y,z,z,z,A,A,A,A,A,A,A,A,C,C,C,E,E,E,E,E,E,E,I,I,I,I,I,I,L,N,N,O,O,O,O,O,O,O,O,S,S,U,U,U,U,U,Y,Z,Z,Z");
        return $this->remove_emoji(str_replace($search, $replace, $texto));
    }

    private function remove_emoji($string){
        return preg_replace('%(?:
        \xF0[\x90-\xBF][\x80-\xBF]{2}		# planes 1-3
        | [\xF1-\xF3][\x80-\xBF]{3}			# planes 4-15
        | \xF4[\x80-\x8F][\x80-\xBF]{2}		# plane 16
        )%xs', '  ', $string);      
    }

    // Retorna a quantidade de caracteres do texto $tx com dois dígitos
    private function cpm($tx){
        if (strlen($tx) > 99) {
            die( "Tamanho máximo deve ser 99, inválido: $tx possui " . strlen($tx) . " caracteres." );
        }
        return $this->c2(strlen($tx));
    }

    // Trata os casos onde o tamanho do campo for < 10 acrescentando o dígito 0 a esquerda
    private function c2($input){
        return str_pad($input, 2, "0", STR_PAD_LEFT);
    }

    // Calcula o CRC-16/CCITT-FALSE
    private function crcChecksum($str) {
        
        $charCodeAt = function($str, $i) {
            return ord(substr($str, $i, 1));
        };
        
        $crc = 0xFFFF;
        $strlen = strlen($str);
        for($c = 0; $c < $strlen; $c++) {
            $crc ^= $charCodeAt($str, $c) << 8;
            for($i = 0; $i < 8; $i++) {
                if($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc = $crc << 1;
                }
            }
        }
        
        $hex = $crc & 0xFFFF;
        $hex = dechex($hex);
        $hex = strtoupper($hex);
        $hex = str_pad($hex, 4, '0', STR_PAD_LEFT);
        return $hex;
    }

    public function geraQRCodePix( $chave, $valor, $identificador, $size = '200' ){
        
        if (!empty($valor) && $valor !== '0') {
            $valor = str_replace(',', '.', $valor);
        } else {
            $valor = '0';
        }
        
        $px[00] = "01";
        $px[26][00] = "BR.GOV.BCB.PIX";
        $px[26][01] = $chave;
        $px[52] = "0000";
        $px[53] = "986";
        $px[54] = $valor;
        $px[58] = "BR";
        $px[62][05] = "***";
        
        $pix = $this->montaPix($px);
        
        // Adiciona o campo do CRC no fim da linha do pix
        $pix .= "6304";
        
        // Calcula o checksum CRC16 e acrescenta ao final
        $pix .= $this->crcChecksum($pix);
        
        $data = "http://quickchart.io/chart?chs=".$size."&cht=qr&chld=l|1&chl=" . urlencode($pix);
        
        return $data;
    }
}

new WP_PIX_QRCode();
