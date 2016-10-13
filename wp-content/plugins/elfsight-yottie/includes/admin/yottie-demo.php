<div class="yottie-demo">
    <form class="yottie-demo-form">
        <input class="yottie-demo-result" type="hidden" name="options" value="">

        <div class="yottie-demo-accordion">
            <div class="yottie-demo-accordion-item-active yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Source', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-channel yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('YouTube channel URL', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                        <div class="yottie-demo-field-group-description">
                            <?php _e('Insert URL of a YouTube channel to display its information and videos in the plugin.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                        </div>

                        <div class="yottie-demo-field">
                            <input class="yottie-demo-text-input" type="text" name="channel" placeholder="<?php _e('Add a channel URL', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>">
                        </div>
                    </div>

                    <div class="yottie-demo-source-groups yottie-demo-field-group">
                        <input type="hidden" name="sourceGroups" value="">

                        <div class="yottie-demo-field-group-name">
                            <?php _e('Source groups', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>

                            <span class="yottie-demo-tooltip">
                                <span class="yottie-demo-tooltip-trigger">?</span>

                                <span class="yottie-demo-tooltip-content">
                                    <span class="yottie-demo-tooltip-content-inner">
                                        <?php _e('Create custom video playlists from unlimited combinations of YouTube sources to display them instead of videos from the specified channel above in the plugin.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                    </span>
                                </span>
                            </span>
                        </div>

                        <div class="yottie-demo-field-group-description">
                            <?php _e('Organize videos in your channel or create custom groups of videos from any combination of YouTube sources (channels, playlists, videos).', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                        </div>

                        <div class="yottie-demo-source-groups-items"></div>

                        <button class="yottie-demo-source-groups-add">
                            <span class="yottie-demo-icon-plus-white-medium yottie-demo-icon"></span>
                            <span class="yottie-demo-source-groups-add-label"><?php _e('Add group', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                        </button>

                        <template class="yottie-demo-template-source-group yottie-demo-template">
                            <div class="yottie-demo-source-groups-item">
                                <div class="yottie-demo-toggle">
                                    <div class="yottie-demo-toggle-trigger">
                                        <div class="yottie-demo-source-groups-item-title">Untitled</div>

                                        <div class="yottie-demo-source-groups-item-toggle-arrow"></div>    
                                    </div>

                                    <div class="yottie-demo-toggle-content">
                                        <div class="yottie-demo-source-groups-item-name yottie-demo-field">
                                            <div class="yottie-demo-field-name">
                                                <?php _e('Group name', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>

                                                <span class="yottie-demo-tooltip">
                                                    <span class="yottie-demo-tooltip-trigger">?</span>

                                                    <span class="yottie-demo-tooltip-content">
                                                        <span class="yottie-demo-tooltip-content-inner">
                                                            <?php _e('Give a name to your custom video group. It will be displayed in tabs. If you leave it empty, "Untitled" name will be set.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                                        </span>
                                                    </span>
                                                </span>
                                            </div>

                                            <label>
                                                <input type="text" name="sourceGroupName">
                                            </label>
                                        </div>

                                        <div class="yottie-demo-field">
                                            <div class="yottie-demo-field-name">
                                                <?php _e('Sources (channels, playlists, videos)', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                            </div>

                                            <div class="yottie-demo-source-groups-item-sources"></div>
                                        </div>

                                        <div class="yottie-demo-source-groups-item-remove">
                                            <span class="yottie-demo-icon-trash-white-small yottie-demo-icon"></span>
                                            <span class="yottie-demo-source-groups-item-remove-label"><?php _e('Delete group', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template class="yottie-demo-template-source-group-source yottie-demo-template">
                            <div class="yottie-demo-source-groups-item-sources-item">
                                <input type="text" name="sourceGroupSources[]" placeholder="<?php _e('Add a YouTube source URL', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>" autocomplete="off">

                                <div class="yottie-demo-source-groups-item-sources-item-remove" title="<?php _e('Remove this source', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>">
                                    <span class="yottie-demo-icon-remove-white-small yottie-demo-icon"></span>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div class="yottie-demo-order yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('Order', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                        <div class="yottie-demo-field-group-description">
                            <?php _e('Choose sort order of videos in the gallery.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                        </div>

                        <div class="yottie-demo-field">
                            <select class="yottie-demo-select-order yottie-demo-select" name="order">
                                <option value="" selected><?php _e('Default', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="date#desc"><?php _e('Date: new to old', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="date#asc"><?php _e('Date: old to new', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="views#desc"><?php _e('Views: high to low', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="views#asc"><?php _e('Views: low to high', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="likes#desc"><?php _e('Likes: high to low', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="likes#asc"><?php _e('Likes: low to high', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="dislikes#desc"><?php _e('Dislikes: high to low', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="dislikes#asc"><?php _e('Dislikes: low to high', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="position#desc"><?php _e('Position: high to low', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="position#asc"><?php _e('Position: low to high', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="comments#desc"><?php _e('Comments: high to low', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="comments#asc"><?php _e('Comments: low to high', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="random"><?php _e('Random', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-cache-time yottie-demo-field">
                            <div class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Сache time', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                            <span class="yottie-demo-range-container">
                                <input class="yottie-demo-range-input" type="text" name="cacheTime">
                                <span class="yottie-demo-range" data-min="0" data-step="100" data-max="86400"></span>
                            </span>

                            <span class="yottie-demo-field-hint"><?php _e('s', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                            <span class="yottie-demo-tooltip">
                                <span class="yottie-demo-tooltip-trigger">?</span>

                                <span class="yottie-demo-tooltip-content">
                                    <span class="yottie-demo-tooltip-content-inner">
                                        <?php _e('It defines how long in seconds a data from YouTube will be cached in a client side database IndexedDB. Set "0" to turn the cache off.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                    </span>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Sizes', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-field">
                        <label class="yottie-demo-width">
                            <span class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Width', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                            <span class="yottie-demo-range-container">
                                <input type="hidden" name="width" value="auto">
                                <input class="yottie-demo-range-input" type="text" name="width">
                                <span class="yottie-demo-range" data-min="100" data-step="10" data-max="2580"></span>
                            </span>
                        </label>

                        <label class="yottie-demo-width-auto">
                            <input class="yottie-demo-checkbox" type="checkbox" name="widthAuto" value="true">
                            <span class="yottie-demo-checkbox-label"><?php _e('Responsive', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Language', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-lang yottie-demo-field">
                        <span class="yottie-demo-field-name-inline yottie-demo-field-name">
                            <?php _e('Language', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                        </span>

                        <select class="yottie-demo-select-language yottie-demo-select" name="lang">
                            <option value="de"><?php _e('Deutsch', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="en" selected><?php _e('English', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="es"><?php _e('Espa&ntilde;ol', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="fr"><?php _e('Fran&ccedil;ais', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="hr"><?php _e('Hrvatski', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="it"><?php _e('Italiano', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="nl"><?php _e('Nederlands', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="no"><?php _e('Norsk', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="pl"><?php _e('Polski', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="pt-BR"><?php _e('Portugu&ecirc;s', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="sv"><?php _e('Svenska', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="tr"><?php _e('T&uuml;rk&ccedil;e', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="ru"><?php _e('&#x0420;&#x0443;&#x0441;&#x0441;&#x043a;&#x0438;&#x0439;', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="hi"><?php _e('&#x939;&#x93F;&#x928;&#x94D;&#x926;&#x940;', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="zh-HK"><?php _e('&#x4e2d;&#x6587;', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            <option value="ja"><?php _e('&#x65e5;&#x672c;&#x8a9e;', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                        </select>

                        <span class="yottie-demo-tooltip">
                            <span class="yottie-demo-tooltip-trigger">?</span>

                            <span class="yottie-demo-tooltip-content">
                                <span class="yottie-demo-tooltip-content-inner">
                                    <?php _e('Choose one of 16 languages<br> of Plugin\' UI', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                </span>
                            </span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Header', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-header-visible yottie-demo-field-group">
                        <label>
                            <input type="hidden" name="headerVisible" value="false">
                            <input class="yottie-demo-checkbox" type="checkbox" name="headerVisible" value="true">
                            <span class="yottie-demo-checkbox-label"><?php _e('Show header', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                        </label>
                    </div>

                    <div class="yottie-demo-header-layout yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('Header layout', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                        <div class="yottie-demo-multiswitch">
                            <label class="yottie-demo-multiswitch-item">
                                <input type="radio" name="headerLayout" value="classic" checked>
                                <span class="yottie-demo-icon-header-layout-classic-white yottie-demo-icon"></span>
                                <span class="yottie-demo-icon-header-layout-classic-black yottie-demo-icon-active yottie-demo-icon"></span>
                                <span class="yottie-demo-multiswitch-item-label"><?php _e('Classic', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </label>

                            <label class="yottie-demo-multiswitch-item">
                                <input type="radio" name="headerLayout" value="accent">
                                <span class="yottie-demo-icon-header-layout-accent-white yottie-demo-icon"></span>
                                <span class="yottie-demo-icon-header-layout-accent-black yottie-demo-icon-active yottie-demo-icon"></span>
                                <span class="yottie-demo-multiswitch-item-label"><?php _e('Accent', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </label>

                            <label class="yottie-demo-multiswitch-item">
                                <input type="radio" name="headerLayout" value="minimal">
                                <span class="yottie-demo-icon-header-layout-minimal-white yottie-demo-icon"></span>
                                <span class="yottie-demo-icon-header-layout-minimal-black yottie-demo-icon-active yottie-demo-icon"></span>
                                <span class="yottie-demo-multiswitch-item-label"><?php _e('Minimal', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </label>
                        </div>
                    </div>

                    <div class="yottie-demo-header-info yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('Header info', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
                        <input type="hidden" name="headerInfo" value="">

                        <div class="yottie-demo-field-col-1-2">
                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="headerInfo[]" value="logo">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Logo', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="headerInfo[]" value="banner">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Banner', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="headerInfo[]" value="channelName">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Channel name', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="headerInfo[]" value="channelDescription">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Channel description', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="yottie-demo-field-col-1-2">
                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="headerInfo[]" value="videosCounter">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Videos counter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="headerInfo[]" value="subscribersCounter">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Subscribers counter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="headerInfo[]" value="viewsCounter">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Views counter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="headerInfo[]" value="subscribeButton">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Subscribe button', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="yottie-demo-field-group">
                        <label class="yottie-demo-header-channel-name yottie-demo-field">
                            <span class="yottie-demo-field-name"><?php _e('Custom channel name', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                            <input class="yottie-demo-text-input" type="text" name="headerChannelName">
                        </label>

                        <label class="yottie-demo-header-channel-description yottie-demo-field">
                            <span class="yottie-demo-field-name"><?php _e('Custom channel description', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                            <textarea class="yottie-demo-text-input" type="text" name="headerChannelDescription"></textarea>
                        </label>

                        <label class="yottie-demo-header-channel-logo yottie-demo-field">
                            <span class="yottie-demo-field-name"><?php _e('Custom channel logo image URL (100x100)', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                            <input class="yottie-demo-text-input" type="text" name="headerChannelLogo">
                        </label>

                        <label class="yottie-demo-header-channel-banner yottie-demo-field">
                            <span class="yottie-demo-field-name"><?php _e('Custom channel banner image URL (2120x352)', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                            <input class="yottie-demo-text-input" type="text" name="headerChannelBanner">
                        </label>
                    </div>  
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Groups', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-groups-visible yottie-demo-field-group">
                        <label>
                            <input type="hidden" name="groupsVisible" value="false">
                            <input class="yottie-demo-checkbox" type="checkbox" name="groupsVisible" value="true">
                            <span class="yottie-demo-checkbox-label"><?php _e('Show groups', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                            <span class="yottie-demo-tooltip">
                                <span class="yottie-demo-tooltip-trigger">?</span>

                                <span class="yottie-demo-tooltip-content">
                                    <span class="yottie-demo-tooltip-content-inner">
                                        <?php _e('Show or hide the tabs navigation in groups.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                    </span>
                                </span>
                            </span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Content', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-grid yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('Grid', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                        <div class="yottie-demo-field">
                            <label class="yottie-demo-columns">
                                <span class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Columns', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                                <div class="yottie-demo-numeric" data-min="1">
                                    <div class="yottie-demo-numeric-decrease"></div>
                                    <input type="text" name="contentColumns" autocomplete="off">
                                    <div class="yottie-demo-numeric-increase"></div>
                                </div>
                            </label>
                        </div>

                        <div class="yottie-demo-field">
                            <label class="yottie-demo-rows">
                                <span class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Rows', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                                <div class="yottie-demo-numeric" data-min="1">
                                    <div class="yottie-demo-numeric-decrease"></div>
                                    <input type="text" name="contentRows" autocomplete="off">
                                    <div class="yottie-demo-numeric-increase"></div>
                                </div>
                            </label>
                        </div>

                        <div class="yottie-demo-field">
                            <label>
                                <span class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Gutter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                                <span class="yottie-demo-range-container">
                                    <input class="yottie-demo-range-input" type="text" name="contentGutter">
                                    <span class="yottie-demo-range" data-min="0" data-max="200"></span>
                                </span>

                                <span class="yottie-demo-field-hint"><?php _e('px', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </label>
                        </div>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-controls yottie-demo-field-col-3-4">
                            <div class="yottie-demo-field">
                                <div class="yottie-demo-field-name">
                                    <?php _e('Navigation controls', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                </div>

                                <label class="yottie-demo-controls-item">
                                    <input type="hidden" name="contentArrowsControl" value="false">
                                    <input class="yottie-demo-checkbox" type="checkbox" name="contentArrowsControl" value="true">
                                    <span class="yottie-demo-icon-control-arrows-white yottie-demo-icon"></span>
                                    <span class="yottie-demo-icon-control-arrows-black yottie-demo-icon-active yottie-demo-icon"></span>
                                    <span class="yottie-demo-controls-item-label"><?php _e('Arrows', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-controls-item">
                                    <input type="hidden" name="contentScrollControl" value="false">
                                    <input class="yottie-demo-checkbox" type="checkbox" name="contentScrollControl" value="true">
                                    <span class="yottie-demo-icon-control-scroll-white yottie-demo-icon"></span>
                                    <span class="yottie-demo-icon-control-scroll-black yottie-demo-icon-active yottie-demo-icon"></span>
                                    <span class="yottie-demo-controls-item-label"><?php _e('Scroll', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-controls-item">
                                    <input type="hidden" name="contentDragControl" value="false">
                                    <input class="yottie-demo-checkbox" type="checkbox" name="contentDragControl" value="true">
                                    <span class="yottie-demo-icon-control-drag-white yottie-demo-icon"></span>
                                    <span class="yottie-demo-icon-control-drag-black yottie-demo-icon-active yottie-demo-icon"></span>
                                    <span class="yottie-demo-controls-item-label"><?php _e('Drag', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="yottie-demo-field-col-1-4">
                            <div class="yottie-demo-free-mode yottie-demo-field">
                                <label>
                                    <input type="hidden" name="contentFreeMode" value="false">
                                    <input class="yottie-demo-checkbox" type="checkbox" name="contentFreeMode" value="true">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Free mode', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input type="hidden" name="contentScrollbar" value="false">
                                    <input class="yottie-demo-checkbox" type="checkbox" name="contentScrollbar" value="true">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Scrollbar', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-field">
                            <div class="yottie-demo-field-name"><?php _e('Direction', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                            <div class="yottie-demo-multiswitch">
                                <label class="yottie-demo-multiswitch-item">
                                    <input type="radio" name="contentDirection" value="horizontal" checked>
                                    <span class="yottie-demo-icon-direction-horizontal-white yottie-demo-icon"></span>
                                    <span class="yottie-demo-icon-direction-horizontal-black yottie-demo-icon-active yottie-demo-icon"></span>
                                    <span class="yottie-demo-multiswitch-item-label"><?php _e('Horizontal', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-multiswitch-item">
                                    <input type="radio" name="contentDirection" value="vertical">
                                    <span class="yottie-demo-icon-direction-vertical-white yottie-demo-icon"></span>
                                    <span class="yottie-demo-icon-direction-vertical-black yottie-demo-icon-active yottie-demo-icon"></span>
                                    <span class="yottie-demo-multiswitch-item-label"><?php _e('Vertical', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-field">
                            <div class="yottie-demo-field-name"><?php _e('Animation effect', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                            <div class="yottie-demo-multiswitch">
                                <label class="yottie-demo-multiswitch-item">
                                    <input type="radio" name="contentTransitionEffect" value="slide" checked>
                                    <span class="yottie-demo-multiswitch-item-label"><?php _e('Slide', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-multiswitch-item">
                                    <input type="radio" name="contentTransitionEffect" value="fade">
                                    <span class="yottie-demo-multiswitch-item-label"><?php _e('Fade', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-multiswitch-item">
                                    <input type="radio" name="contentTransitionEffect" value="coverflow">
                                    <span class="yottie-demo-multiswitch-item-label"><?php _e('Coverflow', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-multiswitch-item">
                                    <input type="radio" name="contentTransitionEffect" value="cube">
                                    <span class="yottie-demo-multiswitch-item-label"><?php _e('Cube', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-multiswitch-item">
                                    <input type="radio" name="contentTransitionEffect" value="flip">
                                    <span class="yottie-demo-multiswitch-item-label"><?php _e('Flip', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-speed yottie-demo-field">
                            <div class="yottie-demo-field-name"><?php _e('Animation speed', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                            <span class="yottie-demo-range-container">
                                <input class="yottie-demo-range-input" type="text" name="contentTransitionSpeed">
                                <span class="yottie-demo-range" data-min="0" data-step="100" data-max="3000"></span>
                            </span>

                            <span class="yottie-demo-field-hint"><?php _e('ms', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                        </div>

                        <!--div class="yottie-demo-easing yottie-demo-field">
                            <div class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Easing', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                            <select class="yottie-demo-select" name="contentTransitionEasing">
                                <option value="linear"><?php _e('linear', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="ease" selected><?php _e('ease', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="ease-in"><?php _e('ease-in', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="ease-out"><?php _e('ease-out', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="ease-in-out"><?php _e('ease-in-out', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            </select>
                        </div-->
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-field-col-1-2">
                            <div class="yottie-demo-auto yottie-demo-field">
                                <div class="yottie-demo-field-name"><?php _e('Autorotation', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                                <span class="yottie-demo-range-container">
                                    <input class="yottie-demo-range-input" type="text" name="contentAuto">
                                    <span class="yottie-demo-range" data-min="0" data-step="100" data-max="10000"></span>
                                </span>

                                <span class="yottie-demo-field-hint"><?php _e('ms', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </div>
                        </div>

                        <div class="yottie-demo-field-col-1-2">
                            <div class="yottie-demo-auto-hover-pause yottie-demo-field">
                                <label>
                                    <input type="hidden" name="contentAutoPauseOnHover" value="false">
                                    <input class="yottie-demo-checkbox" type="checkbox" name="contentAutoPauseOnHover" value="true">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Pause on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="yottie-demo-responsive yottie-demo-field-group">
                        <input type="hidden" name="contentResponsive" value="">

                        <div class="yottie-demo-field-group-name">
                            <?php _e('Responsive breakpoints', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                        </div>

                        <div class="yottie-demo-field-group-description">
                            <?php _e('Specify the breakpoints to set the columns, rows and gutter in the grid depending on a window width.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                        </div>

                        <div class="yottie-demo-responsive-items">
                            <template class="yottie-demo-template-responsive-item yottie-demo-template">
                                <div class="yottie-demo-responsive-item">
                                    <div class="yottie-demo-responsive-item-remove">
                                        <span class="yottie-demo-icon-remove-white-small yottie-demo-icon"></span>
                                    </div>

                                    <div class="yottie-demo-field">
                                        <label>
                                            <span class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Window width', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                                            <span class="yottie-demo-range-container">
                                                <input class="yottie-demo-range-input" type="text" name="responsiveWindowWidth">
                                                <span class="yottie-demo-range" data-min="100" data-step="10" data-max="2580"></span>
                                            </span>

                                            <span class="yottie-demo-field-hint"><?php _e('px', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                        </label>
                                    </div>

                                    <div class="yottie-demo-responsive-item-columns yottie-demo-field">
                                        <label>
                                            <span class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Columns', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                                            <div class="yottie-demo-numeric" data-min="1">
                                                <div class="yottie-demo-numeric-decrease"></div>
                                                <input type="text" name="responsiveColumns" autocomplete="off">
                                                <div class="yottie-demo-numeric-increase"></div>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="yottie-demo-responsive-item-rows yottie-demo-field">
                                        <label>
                                            <span class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Rows', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                                            <div class="yottie-demo-numeric" data-min="1">
                                                <div class="yottie-demo-numeric-decrease"></div>
                                                <input type="text" name="responsiveRows" autocomplete="off">
                                                <div class="yottie-demo-numeric-increase"></div>
                                            </div>
                                        </label>
                                    </div>

                                    <div class="yottie-demo-responsive-item-gutter yottie-demo-field">
                                        <label>
                                            <span class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Gutter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                                            <span class="yottie-demo-range-container">
                                                <input class="yottie-demo-range-input" type="text" name="responsiveGutter">
                                                <span class="yottie-demo-range" data-min="0" data-max="200"></span>
                                            </span>

                                            <span class="yottie-demo-field-hint"><?php _e('px', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                        </label>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <button class="yottie-demo-responsive-add-item">
                            <span class="yottie-demo-icon-plus-white-medium yottie-demo-icon"></span>
                            <span class="yottie-demo-responsive-add-item-label"><?php _e('Add breakpoint', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Video', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-video-layout yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('Video layout', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                        <div class="yottie-demo-multiswitch">
                            <label class="yottie-demo-multiswitch-item">
                                <input type="radio" name="videoLayout" value="classic" checked>
                                <span class="yottie-demo-icon-video-layout-classic-white yottie-demo-icon"></span>
                                <span class="yottie-demo-icon-video-layout-classic-black yottie-demo-icon-active yottie-demo-icon"></span>
                                <span class="yottie-demo-multiswitch-item-label"><?php _e('Classic', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </label>

                            <label class="yottie-demo-multiswitch-item">
                                <input type="radio" name="videoLayout" value="cinema">
                                <span class="yottie-demo-icon-video-layout-cinema-white yottie-demo-icon"></span>
                                <span class="yottie-demo-icon-video-layout-cinema-black yottie-demo-icon-active yottie-demo-icon"></span>
                                <span class="yottie-demo-multiswitch-item-label"><?php _e('Cinema', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </label>

                            <label class="yottie-demo-multiswitch-item">
                                <input type="radio" name="videoLayout" value="horizontal">
                                <span class="yottie-demo-icon-video-layout-horizontal-white yottie-demo-icon"></span>
                                <span class="yottie-demo-icon-video-layout-horizontal-black yottie-demo-icon-active yottie-demo-icon"></span>
                                <span class="yottie-demo-multiswitch-item-label"><?php _e('Horizontal', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </label>
                        </div>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-play-mode yottie-demo-field">
                            <span class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Video play mode', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                            <select class="yottie-demo-select" name="videoPlayMode">
                                <option value="popup" selected><?php _e('Popup', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="inline"><?php _e('Inline', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="youtube"><?php _e('YouTube', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            </select>

                            <span class="yottie-demo-tooltip">
                                <span class="yottie-demo-tooltip-trigger">?</span>

                                <span class="yottie-demo-tooltip-content">
                                    <span class="yottie-demo-tooltip-content-inner">
                                        <?php _e('Choose the mode of watching videos: in popup, directly in the video gallery, or in a new browser tab right in YouTube.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                    </span>
                                </span>
                            </span>
                        </div>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('Video Info', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
                        <input type="hidden" name="videoInfo" value="">

                        <div class="yottie-demo-field-col-1-2">
                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="videoInfo[]" value="playIcon">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Play icon', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="videoInfo[]" value="duration">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Duration', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="videoInfo[]" value="title">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Title', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="videoInfo[]" value="date">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Date', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="yottie-demo-field-col-1-2">
                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="videoInfo[]" value="description">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Description', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="videoInfo[]" value="viewsCounter">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Views counter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="videoInfo[]" value="likesCounter">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Likes counter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="videoInfo[]" value="commentsCounter">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Comments counter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Popup', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-popup-autoplay yottie-demo-field-group">
                        <label>
                            <input type="hidden" name="popupAutoplay" value="false">
                            <input class="yottie-demo-checkbox" type="checkbox" name="popupAutoplay" value="true">
                            <span class="yottie-demo-checkbox-label"><?php _e('Autoplay by openning in popup', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                        </label>
                    </div>

                    <div class="yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('Popup Info', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>
                        <input type="hidden" name="popupInfo" value="">

                        <div class="yottie-demo-field-col-1-2">
                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="popupInfo[]" value="title">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Title', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="popupInfo[]" value="channelLogo">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Channel logo', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="popupInfo[]" value="channelName">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Channel name', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="popupInfo[]" value="subscribeButton">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Subscribe button', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="popupInfo[]" value="viewsCounter">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Views counter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="popupInfo[]" value="likesCounter">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Likes counter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="yottie-demo-field-col-1-2">
                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="popupInfo[]" value="dislikesCounter">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Dislikes counter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="popupInfo[]" value="likesRatio">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Likes ratio', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="popupInfo[]" value="date">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Date', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="popupInfo[]" value="description">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Description', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="popupInfo[]" value="descriptionMoreButton">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Description more button', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>

                            <div class="yottie-demo-field">
                                <label>
                                    <input class="yottie-demo-checkbox" type="checkbox" name="popupInfo[]" value="comments">
                                    <span class="yottie-demo-checkbox-label"><?php _e('Comments', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!--div class="yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('Video switch animation', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                        <div class="yottie-demo-popup-speed yottie-demo-field">
                            <div class="yottie-demo-field-name"><?php _e('Animation speed', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                            <span class="yottie-demo-range-container">
                                <input class="yottie-demo-range-input" type="text" name="popupTransitionSpeed">
                                <span class="yottie-demo-range" data-min="0" data-step="100" data-max="3000"></span>
                            </span>

                            <span class="yottie-demo-field-hint"><?php _e('ms', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                        </div>

                        <div class="yottie-demo-popup-easing yottie-demo-field">
                            <div class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Animation easing', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                            <select class="yottie-demo-select" name="popupTransitionEasing">
                                <option value="linear"><?php _e('linear', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="ease" selected><?php _e('ease', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="ease-in"><?php _e('ease-in', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="ease-out"><?php _e('ease-out', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                                <option value="ease-in-out"><?php _e('ease-in-out', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></option>
                            </select>
                        </div>
                    </div-->
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('Colors', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-color-scheme yottie-demo-field">
                        <span class="yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Color scheme', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                        <select class="yottie-demo-select" name="colorScheme"></select>
                        <input class="yottie-demo-color-scheme-hidden" type="hidden" name="colorScheme">

                        <span class="yottie-demo-tooltip">
                            <span class="yottie-demo-tooltip-trigger">?</span>

                            <span class="yottie-demo-tooltip-content">
                                <span class="yottie-demo-tooltip-content-inner">
                                    <?php _e('Choose one of 4 ready-made color schemes to get the appropriate look of the plugin. Then you can adust desired colors in the choosen scheme using 60 color options.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                </span>
                            </span>
                        </span>
                    </div>

                    <div class="yottie-demo-colors yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('Colors', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                        <div class="yottie-demo-toggle">
                            <div class="yottie-demo-toggle-trigger">
                                <span class="yottie-demo-toggle-trigger-label"><?php _e('Header', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </div>

                            <div class="yottie-demo-toggle-content">
                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorHeaderBg">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Background', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorHeaderBannerOverlay">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Banner overlay', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                                    <span class="yottie-demo-tooltip">
                                        <span class="yottie-demo-tooltip-trigger">?</span>

                                        <span class="yottie-demo-tooltip-content">
                                            <span class="yottie-demo-tooltip-content-inner">
                                                <?php _e('Works with Accent and Minimal header layouts', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                            </span>
                                        </span>
                                    </span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorHeaderChannelName">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Channel name', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorHeaderChannelNameHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Channel name on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorHeaderChannelDescription">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Channel description', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorHeaderAnchor">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Description anchors', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorHeaderAnchorHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Description anchors on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorHeaderCounters">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Counters', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="yottie-demo-toggle">
                            <div class="yottie-demo-toggle-trigger">
                                <span class="yottie-demo-toggle-trigger-label"><?php _e('Groups', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </div>

                            <div class="yottie-demo-toggle-content">
                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorGroupsBg">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Background', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorGroupsLink">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Link', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                                    <span class="yottie-demo-tooltip">
                                        <span class="yottie-demo-tooltip-trigger">?</span>

                                        <span class="yottie-demo-tooltip-content">
                                            <span class="yottie-demo-tooltip-content-inner">
                                                <?php _e('Color of inactive group links when there is more than one link in the tabs.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                            </span>
                                        </span>
                                    </span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorGroupsLinkHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Link on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                                    <span class="yottie-demo-tooltip">
                                        <span class="yottie-demo-tooltip-trigger">?</span>

                                        <span class="yottie-demo-tooltip-content">
                                            <span class="yottie-demo-tooltip-content-inner">
                                                <?php _e('Color of inactive group links on hover when there is more than one link in the tabs.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                            </span>
                                        </span>
                                    </span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorGroupsLinkActive">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Active link', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorGroupsHighlight">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Highlight', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorGroupsHighlightHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Highlight on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorGroupsHighlightActive">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Active link highlight', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="yottie-demo-toggle">
                            <div class="yottie-demo-toggle-trigger">
                                <span class="yottie-demo-toggle-trigger-label"><?php _e('Content', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </div>

                            <div class="yottie-demo-toggle-content">
                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorContentBg">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Background', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorContentArrows">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Arrows', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorContentArrowsHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Arrows on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorContentArrowsBg">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Arrows background', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorContentArrowsBgHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Arrows background on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorContentScrollbarBg">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Scrollbar background', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorContentScrollbarSliderBg">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Scrollbar slider background', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="yottie-demo-toggle">
                            <div class="yottie-demo-toggle-trigger">
                                <span class="yottie-demo-toggle-trigger-label"><?php _e('Video', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </div>

                            <div class="yottie-demo-toggle-content">
                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoBg">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Background', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoOverlay">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Overlay', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>

                                    <span class="yottie-demo-tooltip">
                                        <span class="yottie-demo-tooltip-trigger">?</span>

                                        <span class="yottie-demo-tooltip-content">
                                            <span class="yottie-demo-tooltip-content-inner">
                                                <?php _e('Works with Cinema video layout', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                                            </span>
                                        </span>
                                    </span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoPlayIcon">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Play icon', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoPlayIconHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Play icon on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoDuration">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Duration', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoDurationBg">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Duration background', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoTitle">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Title', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoTitleHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Title on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoDate">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Date', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoDescription">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Description', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoAnchor">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Description anchors', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoAnchorHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Description anchors on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorVideoCounters">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Counters', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>

                        <div class="yottie-demo-toggle">
                            <div class="yottie-demo-toggle-trigger">
                                <span class="yottie-demo-toggle-trigger-label"><?php _e('Popup', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                            </div>

                            <div class="yottie-demo-toggle-content">
                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupBg">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Background', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupOverlay">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Overlay', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupTitle">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Title', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupChannelName">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Channel name', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupChannelNameHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Channel name on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupViewsCounter">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Views counter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupLikesRatio">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Likes ratio', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupDislikesRatio">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Dislikes ratio', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupLikesCounter">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Likes counter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupDislikesCounter">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Dislikes counter', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupDate">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Date', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupDescription">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Description', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupAnchor">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Anchors', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupAnchorHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Anchors on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupDescriptionMoreButton">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Description more button', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupDescriptionMoreButtonHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Description more button on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupCommentsUsername">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Comments username', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupCommentsUsernameHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Comments username on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupCommentsPassedTime">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Comments passed time', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupCommentsText">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Comments text', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupCommentsLikes">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Comments likes', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupControls">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Controls', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupControlsHover">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Controls on hover', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupControlsMobile">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Mobile controls', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>

                                <label class="yottie-demo-field">
                                    <input class="yottie-demo-colorpicker" type="text" name="colorPopupControlsMobileBg">
                                    <span class="yottie-demo-colorpicker-label yottie-demo-field-name-inline yottie-demo-field-name"><?php _e('Mobile controls background', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="yottie-demo-accordion-item">
                <div class="yottie-demo-accordion-item-trigger"><?php _e('AdSense', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                <div class="yottie-demo-accordion-item-content">
                    <div class="yottie-demo-channel yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('AdSense client', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                        <div class="yottie-demo-field-group-description">
                            <?php _e('Yottie supports AdSense Advertisement platform. Specify AdSense client (pubId) to turn it on.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                        </div>

                        <div class="yottie-demo-field">
                            <input class="yottie-demo-text-input" type="text" name="adsClient">
                        </div>
                    </div>

                    <div class="yottie-demo-channel yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('AdSense content slot', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                        <div class="yottie-demo-field-group-description">
                            <?php _e('Slot identifier for adv block in content (video gallery).', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                        </div>

                        <div class="yottie-demo-field">
                            <input class="yottie-demo-text-input" type="text" name="adsSlotsContent">
                        </div>
                    </div>

                    <div class="yottie-demo-channel yottie-demo-field-group">
                        <div class="yottie-demo-field-group-name"><?php _e('AdSense popup slot', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?></div>

                        <div class="yottie-demo-field-group-description">
                            <?php _e('Slot identifier for adv block in popup.', ELFSIGHT_YOTTIE_TEXTDOMAIN); ?>
                        </div>

                        <div class="yottie-demo-field">
                            <input class="yottie-demo-text-input" type="text" name="adsSlotsPopup">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="yottie-demo-preview-container">
        <div class="yottie-demo-preview"></div>
        <div class="yottie-demo-preview-clone"></div>
    </div>
</div>