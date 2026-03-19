# WP PIX QR Code

[![WordPress Version](https://img.shields.io/badge/WordPress-5.0%2B-0073AA?style=flat-square&logo=wordpress)](https://wordpress.org/)
[![PHP Version](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=flat-square&logo=php)](https://www.php.net/)
[![License](https://img.shields.io/badge/License-GPLv2-blue?style=flat-square)](https://www.gnu.org/licenses/gpl-2.0.html)
[![Stable Version](https://img.shields.io/badge/version-1.0.0-success?style=flat-square)](https://github.com/wpfuse/wp-pix)

**Plugin leve e eficiente para gerar QR codes PIX no WordPress com shortcode e bloco Gutenberg.**

O **WP PIX QR Code** é um plugin extremamente leve e performático que permite gerar QR codes PIX válidos diretamente no seu site WordPress. Você pode inserir QR codes PIX em qualquer página ou post usando shortcodes ou o editor de blocos Gutenberg.

---

## 🚀 Características principais

- ⚡ **Extremamente leve** - Apenas um arquivo PHP principal, sem bibliotecas externas pesadas.
- ✅ **QR codes válidos** - Compatível com todos os bancos e apps PIX seguindo o padrão EMV do Banco Central.
- 🧩 **Bloco Gutenberg** - Interface visual nativa no editor de blocos com preview em tempo real.
- 🔢 **Shortcode flexível** - Use em qualquer lugar: `[pix_qr chave="sua-chave" valor="10.50"]`.
- 📱 **Responsivo** - QR codes se adaptam a qualquer tamanho de tela.
- 📐 **Customização básica** - Escolha entre múltiplos tamanhos (150px a 400px) e alinhamentos.
- 💵 **Valor flexível** - Suporte para PIX com valor fixo ou valor livre.
- 🛠️ **Normalização inteligente** - Converte automaticamente vírgula para ponto nos valores decimais.

---

## 📦 Instalação

1. Baixe o plugin e faça upload do arquivo `wp-pix.php` para o diretório `/wp-content/plugins/wp-pix/`.
2. Ative o plugin através do menu **Plugins** no painel administrativo do WordPress.
3. Comece a usar imediatamente via Bloco ou Shortcode!

---

## 🛠️ Como usar

### 1. Bloco Gutenberg
O modo mais fácil de usar se você utiliza o editor moderno do WordPress:
1. No editor, clique no botão **+** e procure por **"QR Code PIX"**.
2. No painel lateral de configurações, insira sua **Chave PIX** (obrigatório).
3. Ajuste o **Valor**, **Identificador**, **Tamanho** e **Alinhamento** conforme necessário.
4. Veja o preview em tempo real e publique sua página.

### 2. Shortcode
Para usuários avançados ou para uso em temas/widgets clássicos:

**Exemplo básico (valor livre):**
```markdown
[pix_qr chave="usuario@email.com"]
```

**Exemplo com valor fixo:**
```markdown
[pix_qr chave="11999999999" valor="25.50"]
```

**Exemplo completo com todos os parâmetros:**
```markdown
[pix_qr chave="sua-chave-pix" valor="100.00" identificador="pedido123" size="300" align="center"]
```

#### Parâmetros do Shortcode

| Parâmetro | Obrigatório | Descrição | Padrão |
| :--- | :---: | :--- | :--- |
| `chave` | Sim | Sua chave PIX (CPF, CNPJ, e-mail, telefone ou chave aleatória). | - |
| `valor` | Não | Valor em reais (ex: `25.50` ou `25,50`). | `0` (livre) |
| `identificador` | Não | Identificador alfanumérico para controle interno. | - |
| `size` | Não | Tamanho em px: `150`, `200`, `300` ou `400`. | `200` |
| `align` | Não | Alinhamento: `left`, `center` ou `right`. | `center` |

---

## ❓ Perguntas Frequentes (FAQ)

> **O QR code funciona com todos os bancos?**
> Sim! O plugin segue o padrão EMV do Banco Central, garantindo compatibilidade total.

> **Posso usar vírgula no valor?**
> Sim! O plugin normaliza automaticamente `25,50` para `25.50`.

> **O que acontece se eu não informar o valor?**
> O QR code será gerado para "valor livre", permitindo que o pagador digite o valor no app.

---

## 📜 Changelog

### 1.0.0
- ✨ Lançamento inicial.
- 🛠️ Suporte a Shortcode e Bloco Gutenberg.
- 🔄 Normalização de valores e validação EMV.

---

## 🤝 Créditos & Suporte

Desenvolvido com ❤️ por [wpfuse](https://github.com/wpfuse).

Para suporte técnico ou sugestões, abra uma issue no GitHub.

---
*Este plugin é distribuído sob a licença GPLv2 ou posterior.*
