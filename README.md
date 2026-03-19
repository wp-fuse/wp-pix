=== PIX QR Code ===
Contributors: wpfuse
Tags: pix, qr-code, pagamento, brasil, gutenberg
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Plugin leve e eficiente para gerar QR codes PIX no WordPress com shortcode e bloco Gutenberg.

== Descrição ==

O WP PIX QR Code é um plugin extremamente leve e performático que permite gerar QR codes PIX válidos diretamente no seu site WordPress. Com uma interface simples e intuitiva, você pode inserir QR codes PIX em qualquer página ou post usando shortcodes ou o editor de blocos Gutenberg.

**Características principais:**

* ✅ **Extremamente leve** - Apenas um arquivo PHP, sem bibliotecas externas
* ✅ **QR codes válidos** - Compatível com todos os bancos e apps PIX
* ✅ **Shortcode simples** - `[pix_qr chave="sua-chave" valor="10.50"]`
* ✅ **Bloco Gutenberg** - Interface visual no editor de blocos
* ✅ **Preview em tempo real** - Veja o QR code enquanto edita
* ✅ **Responsivo** - QR codes se adaptam a qualquer tela
* ✅ **Múltiplos tamanhos** - 150px, 200px, 300px, 400px
* ✅ **Alinhamento flexível** - Esquerda, centro, direita
* ✅ **Valor opcional** - PIX com valor fixo ou livre
* ✅ **Aceita vírgula** - Converte automaticamente vírgula para ponto

**Casos de uso:**

* Lojas online que aceitam PIX
* Sites de serviços e freelancers
* Páginas de doação
* Eventos e ingressos
* Qualquer negócio que aceite PIX

== Instalação ==

1. Faça upload do arquivo `wp-pix.php` para o diretório `/wp-content/plugins/wp-pix-qr/`
2. Ative o plugin através do menu 'Plugins' no WordPress
3. Pronto! O plugin está funcionando

== Como usar ==

**Usando Shortcode:**

Básico (valor livre):
`[pix_qr chave="usuario@email.com"]`

Com valor fixo:
`[pix_qr chave="11999999999" valor="25.50"]`

Completo:
`[pix_qr chave="sua-chave-pix" valor="100.00" identificador="pedido123" size="300" align="center"]`

**Usando Bloco Gutenberg:**

1. No editor de posts/páginas, clique em "+" para adicionar um bloco
2. Procure por "QR Code PIX" ou encontre na categoria "Widgets"
3. Configure os campos no painel lateral:
   - **Chave PIX** (obrigatório): Sua chave PIX
   - **Valor** (opcional): Deixe vazio para valor livre
   - **Identificador** (opcional): Para controle interno
   - **Tamanho**: Escolha entre 150px, 200px, 300px ou 400px
4. Use os controles de alinhamento na barra superior
5. Publique ou atualize a página

**Parâmetros do Shortcode:**

* `chave` (obrigatório) - Sua chave PIX (CPF, CNPJ, e-mail, telefone ou chave aleatória)
* `valor` (opcional) - Valor em reais (ex: "25.50" ou "25,50")
* `identificador` (opcional) - Identificador para controle interno
* `size` (opcional) - Tamanho do QR code: "150", "200", "300" ou "400" (padrão: "200")
* `align` (opcional) - Alinhamento: "left", "center" ou "right" (padrão: "center")

== Perguntas Frequentes ==

= O QR code funciona com todos os bancos? =

Sim! O plugin gera QR codes PIX seguindo o padrão EMV do Banco Central, sendo compatível com todos os bancos e apps que suportam PIX.

= Posso usar vírgula no valor? =

Sim! O plugin aceita tanto vírgula quanto ponto como separador decimal. Exemplos: "25,50" ou "25.50".

= O que acontece se eu não informar o valor? =

O QR code será gerado para valor livre, permitindo que o pagador digite o valor desejado no app do banco.

= Posso personalizar o visual do QR code? =

O plugin gera QR codes padrão em preto e branco. Você pode usar CSS para estilizar o container se necessário.

= O plugin é seguro? =

Sim! O plugin não armazena dados sensíveis e usa apenas funções nativas do WordPress. Todos os dados são sanitizados adequadamente.

= Funciona com cache? =

Sim! O plugin é totalmente compatível com plugins de cache como WP Rocket, W3 Total Cache, etc.

== Screenshots ==

1. Interface do bloco Gutenberg com preview em tempo real
2. Painel de configurações do bloco
3. QR code PIX exibido no frontend
4. Exemplo de shortcode em uso

== Changelog ==

= 1.0.0 =
* Lançamento inicial
* Shortcode `[pix_qr]` funcional
* Bloco Gutenberg com preview em tempo real
* Suporte a múltiplos tamanhos e alinhamentos
* Normalização automática de vírgula para ponto
* QR codes válidos seguindo padrão EMV

== Upgrade Notice ==

= 1.0.0 =
Primeira versão do plugin. Instale para começar a usar QR codes PIX no seu site.

== Suporte ==

Para suporte técnico, dúvidas ou sugestões, entre em contato através do fórum de suporte do WordPress.org.

== Créditos ==

Desenvolvido com ❤️ para a comunidade WordPress brasileira.
