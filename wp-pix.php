<?php
/**
 * Plugin Name: WP PIX QR Code
 * Description: Plugin para gerar QR codes PIX com shortcode e bloco Gutenberg
 * Version: 1.0.1
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
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce'   => wp_create_nonce('wp_pix_generate_qr')
        ));
    }
    
    public function ajax_generate_qr() {
        // Verifica nonce para segurança
        if (!isset($_GET['nonce']) || !wp_verify_nonce($_GET['nonce'], 'wp_pix_generate_qr')) {
            wp_die('Requisição inválida', 403);
        }

        $chave = sanitize_text_field($_GET['chave'] ?? '');
        $valor = sanitize_text_field($_GET['valor'] ?? '0');
        $identificador = sanitize_text_field($_GET['identificador'] ?? '');
        $size = sanitize_text_field($_GET['size'] ?? '200');
        
        if (empty($chave)) {
            wp_die('Chave PIX é obrigatória');
        }
        
        $qr_url = $this->geraQRCodePix($chave, $valor, $identificador, $size);
        echo esc_url($qr_url);
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
        $ret = '';
        foreach ($px as $k => $v) {
            if (!is_array($v)) {
                // Formata o campo valor com 2 dígitos decimais
                if ($k == 54) { $v = number_format($v, 2, '.', ''); }
                else { $v = $this->remove_char_especiais($v); }
                $ret .= $this->c2($k) . $this->cpm($v) . $v;
            }
            else {
                $conteudo = $this->montaPix($v);
                $ret .= $this->c2($k) . $this->cpm($conteudo) . $conteudo;
            }
        }
        return $ret;
    }

    // Remove caracteres especiais (não-alfanuméricos e espaços)
    private function remove_char_especiais($txt){
        return preg_replace('/[\W ]/', '', $this->remove_acentos($txt));
    }

    // Remove acentos convertendo para ASCII
    private function remove_acentos($texto){
        $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $texto);
        if ($transliterated === false) {
            return $texto;
        }
        // Remove caracteres de decoração gerados pelo TRANSLIT (ex: ^, ~, `)
        return preg_replace('/[^\x20-\x7E]/', '', $transliterated);
    }

    // Retorna a quantidade de caracteres do texto com dois dígitos
    private function cpm($tx){
        if (strlen($tx) > 99) {
            return $this->c2(99);
        }
        return $this->c2(strlen($tx));
    }

    // Preenche com zero à esquerda para garantir dois dígitos
    private function c2($input){
        return str_pad($input, 2, '0', STR_PAD_LEFT);
    }

    // Calcula o CRC-16/CCITT-FALSE
    private function crcChecksum($str) {
        $crc = 0xFFFF;
        $strlen = strlen($str);
        for ($c = 0; $c < $strlen; $c++) {
            $crc ^= ord($str[$c]) << 8;
            for ($i = 0; $i < 8; $i++) {
                if ($crc & 0x8000) {
                    $crc = ($crc << 1) ^ 0x1021;
                } else {
                    $crc = $crc << 1;
                }
            }
        }
        
        return str_pad(strtoupper(dechex($crc & 0xFFFF)), 4, '0', STR_PAD_LEFT);
    }

    public function geraQRCodePix($chave, $valor, $identificador = '', $size = '200'){
        
        $px[00] = '01';
        $px[26][00] = 'BR.GOV.BCB.PIX';
        $px[26][01] = $chave;
        $px[52] = '0000';
        $px[53] = '986';

        // Inclui campo de valor apenas quando informado (omite para valor livre)
        if (!empty($valor) && $valor !== '0') {
            $px[54] = str_replace(',', '.', $valor);
        }

        $px[58] = 'BR';

        // Usa o identificador fornecido ou fallback padrão
        $px[62][05] = !empty($identificador) ? $identificador : '***';
        
        $pix = $this->montaPix($px);
        
        // Adiciona o campo do CRC no fim da linha do pix
        $pix .= '6304';
        
        // Calcula o checksum CRC16 e acrescenta ao final
        $pix .= $this->crcChecksum($pix);
        
        return 'https://quickchart.io/chart?chs=' . $size . '&cht=qr&chld=l|1&chl=' . urlencode($pix);
    }
}

new WP_PIX_QRCode();
