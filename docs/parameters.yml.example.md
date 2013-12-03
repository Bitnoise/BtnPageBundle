``` yml
parameters:
    btn_pages:
        ckeditor_conf:
            config:
                stylesSet:
                    -
                        name: 'Italic Title'
                        element: h2
                        styles:
                            font-style: italic
                    -
                        name: Subtitle
                        element: h3
                        styles:
                            color: '#aaa'
                            'font-style''': italic
                    -
                        name: 'Special Container'
                        element: div
                        styles:
                            padding: '5px 10px'
                            background: '#eee'
                            border: '1px solid #ccc'
                    -
                        name: Big
                        element: big
                    -
                        name: Small
                        element: small
                    -
                        name: Typewriter
                        element: tt
                    -
                        name: 'Computer Code'
                        element: code
                    -
                        name: 'Keyboard Phrase'
                        element: kbd
                    -
                        name: 'Sample Text'
                        element: samp
                    -
                        name: Variable
                        element: var
                    -
                        name: 'Deleted Text'
                        element: del
                    -
                        name: 'Inserted Text'
                        element: ins
                    -
                        name: 'Cited Work'
                        element: cite
                    -
                        name: 'Inline Quotation'
                        element: q
                    -
                        name: 'Language: RTL'
                        element: span
                        attributes:
                            dir: rtl
                    -
                        name: 'Language: LTR'
                        element: span
                        attributes:
                            dir: ltr
                    -
                        name: 'Styled image (left)'
                        element: img
                        attributes:
                            class: left
                    -
                        name: 'Styled image (right)'
                        element: img
                        attributes:
                            class: right
                    -
                        name: 'Compact table'
                        element: table
                        attributes:
                            cellpadding: '5'
                            cellspacing: '0'
                            border: '1'
                            bordercolor: '#ccc'
                        styles:
                            border-collapse: collapse
                    -
                        name: 'Borderless Table'
                        element: table
                        styles:
                            border-style: hidden
                            background-color: '#E6E6FA'
                    -
                        name: 'Square Bulleted List'
                        element: ul
                        styles:
                            list-style-type: square
                toolbar:
                    -
                        name: basicstyles
                        items:
                            - Bold
                            - Italic
                            - Underline
                            - Strike
                            - Subscript
                            - Superscript
                            - -
                            - RemoveFormat
                    -
                        name: paragraph
                        items:
                            - NumberedList
                            - BulletedList
                            - -
                            - Outdent
                            - Indent
                            - -
                            - JustifyLeft
                            - JustifyCenter
                            - JustifyRight
                            - JustifyBlock
                    -
                        name: links
                        items:
                            - Link
                            - Unlink
                            - Anchor
                    -
                        name: insert
                        items:
                            - Image
                            - Table
                            - HorizontalRule
                    -
                        name: document
                        items:
                            - Source
                    - /
                    -
                        name: styles
                        items:
                            - Styles
                            - Format
                            - Font
                            - FontSize
                    -
                        name: 'colors'
                        items: ['TextColor','BGColor']
                uiColor: '#ffffff'
        templates:
            show:
                name: show.html.twig
            sidebar:
                name: sidebar.html.twig
                hide_content: false
                fields:
                    content:
                        type: ckeditor
                        mapped: false
                        label: Content
                    sidebar:
                        type: ckeditor
                        mapped: false
                        label: Sidebar
```
