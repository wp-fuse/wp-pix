(function () {
    const { registerBlockType } = wp.blocks;
    const { InspectorControls, BlockControls, AlignmentToolbar } = wp.blockEditor;
    const { PanelBody, TextControl, SelectControl } = wp.components;
    const { createElement: el, Fragment, useState, useEffect } = wp.element;

    // Função para gerar QR Code usando AJAX para o PHP
    function useQRCodeUrl(chave, valor, identificador, size) {
        const [qrUrl, setQrUrl] = useState('');

        useEffect(() => {
            if (!chave) {
                setQrUrl('');
                return;
            }

            const params = new URLSearchParams({
                action: 'wp_pix_generate_qr',
                chave: chave,
                valor: valor || '0',
                identificador: identificador || '',
                size: size
            });

            fetch(wpPixAjax.ajaxurl + '?' + params.toString())
                .then(response => response.text())
                .then(url => setQrUrl(url))
                .catch(() => setQrUrl(''));
        }, [chave, valor, identificador, size]);

        return qrUrl;
    }

    registerBlockType('wp-pix/qr-code', {
        title: 'QR Code PIX',
        icon: el('svg', {
            xmlns: 'http://www.w3.org/2000/svg',
            width: 24,
            height: 24,
            fill: 'currentColor',
            viewBox: '0 0 16 16'
        },
            el('path', { d: 'M0 .5A.5.5 0 0 1 .5 0h3a.5.5 0 0 1 0 1H1v2.5a.5.5 0 0 1-1 0zm12 0a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V1h-2.5a.5.5 0 0 1-.5-.5M.5 12a.5.5 0 0 1 .5.5V15h2.5a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5v-3a.5.5 0 0 1 .5-.5m15 0a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1 0-1H15v-2.5a.5.5 0 0 1 .5-.5M4 4h1v1H4z' }),
            el('path', { d: 'M7 2H2v5h5zM3 3h3v3H3zm2 8H4v1h1z' }),
            el('path', { d: 'M7 9H2v5h5zm-4 1h3v3H3zm8-6h1v1h-1z' }),
            el('path', { d: 'M9 2h5v5H9zm1 1v3h3V3zM8 8v2h1v1H8v1h2v-2h1v2h1v-1h2v-1h-3V8zm2 2H9V9h1zm4 2h-1v1h-2v1h3zm-4 2v-1H8v1z' }),
            el('path', { d: 'M12 9h2V8h-2z' })
        ),
        category: 'widgets',
        attributes: {
            chave: {
                type: 'string',
                default: ''
            },
            valor: {
                type: 'string',
                default: ''
            },
            identificador: {
                type: 'string',
                default: ''
            },
            size: {
                type: 'string',
                default: '200'
            },
            align: {
                type: 'string',
                default: 'center'
            }
        },

        edit: function (props) {
            const { attributes, setAttributes } = props;
            const { chave, valor, identificador, size, align } = attributes;
            const qrUrl = useQRCodeUrl(chave, valor, identificador, size);

            return el(Fragment, {},
                el(BlockControls, {},
                    el(AlignmentToolbar, {
                        value: align,
                        onChange: function (newAlign) {
                            setAttributes({ align: newAlign || 'center' });
                        }
                    })
                ),
                el(InspectorControls, {},
                    el(PanelBody, { title: 'Configurações PIX', initialOpen: true },
                        el(TextControl, {
                            label: 'Chave PIX *',
                            value: chave,
                            onChange: function (value) {
                                setAttributes({ chave: value });
                            },
                            help: 'CPF, CNPJ, e-mail, telefone ou chave aleatória'
                        }),

                        el(TextControl, {
                            label: 'Valor (opcional)',
                            value: valor,
                            onChange: function (value) {
                                setAttributes({ valor: value });
                            },
                            help: 'Deixe vazio para valor livre'
                        }),
                        el(TextControl, {
                            label: 'Identificador (opcional)',
                            value: identificador,
                            onChange: function (value) {
                                setAttributes({ identificador: value });
                            }
                        }),
                        el(SelectControl, {
                            label: 'Tamanho do QR Code',
                            value: size,
                            options: [
                                { label: 'Pequeno (150px)', value: '150' },
                                { label: 'Médio (200px)', value: '200' },
                                { label: 'Grande (300px)', value: '300' },
                                { label: 'Extra Grande (400px)', value: '400' }
                            ],
                            onChange: function (value) {
                                setAttributes({ size: value });
                            }
                        })
                    )
                ),
                el('div', {
                    style: { textAlign: align }
                },
                    qrUrl ?
                        el('img', {
                            src: qrUrl,
                            alt: 'QR Code PIX',
                            style: { maxWidth: '100%', height: 'auto' }
                        }) :
                        chave ?
                            el('div', {
                                style: {
                                    width: size + 'px',
                                    height: size + 'px',
                                    backgroundColor: '#f0f0f0',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    fontSize: '12px',
                                    color: '#666'
                                }
                            }, 'Carregando QR Code...') :
                            el('div', {
                                style: {
                                    width: size + 'px',
                                    height: size + 'px',
                                    backgroundColor: '#f9f9f9',
                                    border: '2px dashed #ccc',
                                    display: 'flex',
                                    alignItems: 'center',
                                    justifyContent: 'center',
                                    textAlign: 'center',
                                    fontSize: '12px',
                                    color: '#999'
                                }
                            }, 'Configure a chave PIX')
                )
            );
        },

        save: function () {
            return null;
        }
    });
})();
