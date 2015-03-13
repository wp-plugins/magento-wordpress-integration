(function() {
    
    var getMenuItems = function( editor, url ) {
        
        var menuItems = {
            'insert-products': { text: "Insert Product(s)", "menu": [] },
            'insert-block': { text: "Insert Block", "menu": [] }
        };
        
        // if mwi shortcodes is active
        
        if( mwi_active_addons.indexOf("mwi-shortcodes") >= 0 ) {
            
            menuItems['insert-products']['menu'].push(
                {
            		text: 'Product(s)',
            		value: '',
            		onclick: function() {
                        editor.windowManager.open( {
                            title: 'Insert Product(s)',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'skus',
                                    label: 'Product SKU(s)',
                                    value: 'comma,separated'
                                },
                                {
                                    type: 'listbox', 
                                    name: 'align', 
                                    label: "Align (Single SKU)", 
                                    'values': [
                                        {text: 'Centre', value: ""},
                                        {text: 'Left', value: "left"},
                                        {text: 'Right', value: "right"}
                                    ]
                                },
                                {
                                    type: 'listbox', 
                                    name: 'title', 
                                    label: 'Show Title?', 
                                    'values': [
                                        {text: 'Yes', value: "true"},
                                        {text: 'No', value: "false"}
                                    ]
                                },
                                {
                                    type: 'listbox', 
                                    name: 'desc', 
                                    label: 'Show Description?', 
                                    'values': [
                                        {text: 'Yes', value: "true"},
                                        {text: 'No', value: "false"}
                                    ]
                                },
                                {
                                    type: 'listbox', 
                                    name: 'img', 
                                    label: 'Show Image?', 
                                    'values': [
                                        {text: 'Yes', value: "true"},
                                        {text: 'No', value: "false"}
                                    ]
                                },
                                {
                                    type: 'textbox',
                                    name: 'img_width',
                                    label: 'Image Width',
                                    value: '400'
                                },
                                {
                                    type: 'listbox', 
                                    name: 'price', 
                                    label: 'Show Price?', 
                                    'values': [
                                        {text: 'Yes', value: "true"},
                                        {text: 'No', value: "false"}
                                    ]
                                },
                                {
                                    type: 'listbox', 
                                    name: 'type', 
                                    label: 'Type', 
                                    'values': [
                                        {text: 'Add to Cart', value: "add"},
                                        {text: 'View Product', value: "view"}
                                    ]
                                },
                                {
                                    type: 'listbox', 
                                    name: 'btn_link', 
                                    label: 'Button Type', 
                                    'values': [
                                        {text: 'Button', value: "button"},
                                        {text: 'Anchor', value: "anchor"}
                                    ]
                                },
                                {
                                    type: 'listbox', 
                                    name: 'btn_color', 
                                    label: 'Button Colour', 
                                    'values': [
                                        {text: 'Blue', value: "blue"},
                                        {text: 'Turquoise', value: "turquoise"},
                                        {text: 'Green', value: "green"},
                                        {text: 'Purple', value: "purple"},
                                        {text: 'Yellow', value: "yellow"},
                                        {text: 'Orange', value: "orange"},
                                        {text: 'Red', value: "red"},
                                        {text: 'Grey', value: "grey"},
                                        {text: 'Light Grey', value: "light-grey"},
                                        {text: 'Dark Grey', value: "dark-grey"}
                                    ]
                                },
                                {
                                    type: 'listbox', 
                                    name: 'cols', 
                                    label: 'Number of Columns', 
                                    'values': [
                                        {text: '1', value: "1"},
                                        {text: '2', value: "2"},
                                        {text: '3', value: "3"},
                                        {text: '4', value: "4"},
                                        {text: '5', value: "5"}
                                    ],
                                    onPostRender: function() {
                                        // Select the third item by default
                                        editor.irstyle_control = this;
                                        this.value('3');
                                    }
                                }
                            ],
                            onsubmit: function( e ) {
                                
                                var params = "";
                                
                                params += 'sku="' + e.data.skus.replace(/\s/g, '') + '" ';                                
                                if(e.data.align != "") params += 'align="' + e.data.align + '" ';
                                if(e.data.title == "false") params += 'title="' + e.data.title + '" ';
                                if(e.data.title_tag == "true") params += 'title_tag="' + e.data.title_tag + '" ';
                                if(e.data.desc == "false") params += 'desc="' + e.data.desc + '" ';
                                if(e.data.img == "false") params += 'img="' + e.data.img + '" ';
                                if(e.data.img == "true") params += 'img_width="' + e.data.img_width + '" ';
                                if(e.data.price == "false") params += 'price="' + e.data.price + '" ';
                                params += 'type="' + e.data.type + '" ';
                                params += 'btn_color="' + e.data.btn_color + '" ';
                                params += 'btn_link="' + e.data.btn_link + '" ';
                                params += 'cols="' + e.data.cols + '" ';
                                
                                editor.insertContent( '[mwi-product ' + params + ']');
                            }
                        });
                    }
                }
            );
                    
            menuItems['insert-block']['menu'].push(
                {
                    text: 'Layout Block',
                    value: '',
                    onclick: function() {
                        editor.windowManager.open( {
                            title: 'Insert Layout Block',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'name',
                                    label: 'Block Name'
                                }
                            ],
                            onsubmit: function( e ) {                                        
                                editor.insertContent( '[mwi-block name="' + e.data.name + '"]');
                            }
                        });
                    }     
                }
            );
                                                    
            menuItems['insert-block']['menu'].push(
                {
                    text: 'Static Block',
                    value: '',
                    onclick: function() {
                        editor.windowManager.open( {
                            title: 'Insert Static Block',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'name',
                                    label: 'Static Block Identifier'
                                }
                            ],
                            onsubmit: function( e ) {                                        
                                editor.insertContent( '[mwi-block name="' + e.data.name + '" type="static"]');
                            }
                        });
                    }    
                }
            );
            
        }
        
        // if mwi category listing is active
        
        if( mwi_active_addons.indexOf("mwi-category-listing") >= 0 ) {
            
            menuItems['insert-products']['menu'].push(
                {
                    text: 'Category Listing',
                    value: '',
                    onclick: function() {
                        editor.windowManager.open( {
                            title: 'Insert Category Listing',
                            body: [
                                {
                                    type: 'textbox',
                                    name: 'cat',
                                    label: 'Category ID',
                                    value: '2'
                                },
                                {
                                    type: 'textbox',
                                    name: 'ppp',
                                    label: 'Products per Page',
                                    value: '12'
                                },
                                {
                                    type: 'listbox', 
                                    name: 'cols', 
                                    label: 'Number of Columns', 
                                    'values': [
                                        {text: '1', value: "1"},
                                        {text: '2', value: "2"},
                                        {text: '3', value: "3"},
                                        {text: '4', value: "4"},
                                        {text: '5', value: "5"}
                                    ],
                                    onPostRender: function() {
                                        // Select the third item by default
                                        editor.irstyle_control = this;
                                        this.value('3');
                                    }
                                },
                                {
                                    type: 'listbox', 
                                    name: 'out_of_stock', 
                                    label: 'Show Out of Stock?', 
                                    'values': [
                                        {text: 'Yes', value: "true"},
                                        {text: 'No', value: "false"}
                                    ],
                                    onPostRender: function() {
                                        // Select the no by default
                                        editor.irstyle_control = this;
                                        this.value('false');
                                    }
                                },
                                {
                                    type: 'listbox', 
                                    name: 'title', 
                                    label: 'Show Title?', 
                                    'values': [
                                        {text: 'Yes', value: "true"},
                                        {text: 'No', value: "false"}
                                    ]
                                },
                                {
                                    type: 'listbox', 
                                    name: 'title_tag', 
                                    label: 'Title Tag', 
                                    'values': [
                                        {text: 'None', value: ""},
                                        {text: 'h1', value: "h1"},
                                        {text: 'h2', value: "h2"},
                                        {text: 'h3', value: "h3"},
                                        {text: 'h4', value: "h4"},
                                        {text: 'h5', value: "h5"},
                                        {text: 'p', value: "p"},
                                        {text: 'span', value: "span"}
                                    ]
                                },
                                {
                                    type: 'listbox', 
                                    name: 'desc', 
                                    label: 'Show Description?', 
                                    'values': [
                                        {text: 'Yes', value: "true"},
                                        {text: 'No', value: "false"}
                                    ]
                                },
                                {
                                    type: 'listbox', 
                                    name: 'img', 
                                    label: 'Show Image?', 
                                    'values': [
                                        {text: 'Yes', value: "true"},
                                        {text: 'No', value: "false"}
                                    ]
                                },
                                {
                                    type: 'textbox',
                                    name: 'img_width',
                                    label: 'Image Width',
                                    value: '400'
                                },
                                {
                                    type: 'listbox', 
                                    name: 'price', 
                                    label: 'Show Price?', 
                                    'values': [
                                        {text: 'Yes', value: "true"},
                                        {text: 'No', value: "false"}
                                    ]
                                },
                                {
                                    type: 'listbox', 
                                    name: 'type', 
                                    label: 'Type', 
                                    'values': [
                                        {text: 'Add to Cart', value: "add"},
                                        {text: 'View Product', value: "view"}
                                    ]
                                },
                                {
                                    type: 'listbox', 
                                    name: 'btn_color', 
                                    label: 'Button Colour', 
                                    'values': [
                                        {text: 'None', value: "none"},
                                        {text: 'Blue', value: "blue"},
                                        {text: 'Turquoise', value: "turquoise"},
                                        {text: 'Green', value: "green"},
                                        {text: 'Purple', value: "purple"},
                                        {text: 'Yellow', value: "yellow"},
                                        {text: 'Orange', value: "orange"},
                                        {text: 'Red', value: "red"},
                                        {text: 'Grey', value: "grey"},
                                        {text: 'Light Grey', value: "light-grey"},
                                        {text: 'Dark Grey', value: "dark-grey"}
                                    ],
                                    onPostRender: function() {
                                        // Select blue by default
                                        editor.irstyle_control = this;
                                        this.value('blue');
                                    }
                                }
                            ],
                            onsubmit: function( e ) {
                                
                                var params = "";
                                
                                params += 'cat="' + e.data.cat.replace(/\s/g, '') + '" ';
                                params += 'ppp="' + e.data.ppp + '" ';
                                params += 'cols="' + e.data.cols + '" ';
                                if(e.data.out_of_stock == "true") params += 'out_of_stock="' + e.data.out_of_stock + '" ';
                                if(e.data.title == "false") params += 'title="' + e.data.title + '" ';
                                if(e.data.title_tag != "") params += 'title_tag="' + e.data.title_tag + '" ';
                                if(e.data.desc == "false") params += 'desc="' + e.data.desc + '" ';
                                if(e.data.img == "false") params += 'img="' + e.data.img + '" ';
                                params += 'img_width="' + e.data.img_width + '" ';
                                if(e.data.price == "false") params += 'price="' + e.data.price + '" ';
                                params += 'type="' + e.data.type + '" ';
                                params += 'btn_color="' + e.data.btn_color + '" ';
                                
                                editor.insertContent( '[mwi-cat-listing ' + params + ']');
                            }
                        });
                    }     
                }
            );
            
        }
        
        // loop through array and remove any top level if it has no sub level entries
        
        for (var key in menuItems) {
            var obj = menuItems[key];
                for (var prop in obj) {
                // important check that this is objects own property 
                // not from prototype prop inherited
                if(obj.hasOwnProperty(prop)){
                    if(prop == "menu") {
                        if(obj[prop].length <= 0) {
                            delete menuItems[key];
                        }
                    }
                }
            }
        }
        
        // convert from object to array
        
        return Object.keys(menuItems).map(function (key) {return menuItems[key]});
    }
    
    tinymce.PluginManager.add('mwi_sc_button', function( editor, url ) {
        editor.addButton( 'mwi_sc_button', {
            title: 'MWI Shortcode',
            icon: 'icon icon-mwi-shortcode',
            type: 'menubutton',
            menu: getMenuItems( editor, url )
        });
    });
})();