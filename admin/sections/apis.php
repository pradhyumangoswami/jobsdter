<?php
/**
 * @package WordPress
 * @subpackage Jobster
 */

if (!function_exists('jobster_admin_apis')): 
    function jobster_admin_apis() {
        add_settings_section(
            'jobster_apis_section',
            __('Job Board External APIs', 'jobster'),
            'jobster_apis_section_callback',
            'jobster_apis_settings'
        );
        add_settings_field(
            'jobster_api_field',
            __('API System', 'jobster'),
            'jobster_api_field_render',
            'jobster_apis_settings',
            'jobster_apis_section'
        );
        add_settings_field(
            'jobster_api_careerjet_locale_field',
            __('Careerjet Locale', 'jobster'),
            'jobster_api_careerjet_locale_field_render',
            'jobster_apis_settings',
            'jobster_apis_section',
            array(
                'class' => 'pxp-apis-settings-field-all pxp-apis-settings-field-careerjet'
            )
        );
        add_settings_field(
            'jobster_api_careerjet_affid_field',
            __('Careerjet Affiliate ID', 'jobster'),
            'jobster_api_careerjet_affid_field_render',
            'jobster_apis_settings',
            'jobster_apis_section',
            array(
                'class' => 'pxp-apis-settings-field-all pxp-apis-settings-field-careerjet'
            )
        );
    }
endif;

if (!function_exists('jobster_apis_section_callback')): 
    function jobster_apis_section_callback() { 
        echo '';
    }
endif;

if (!function_exists('jobster_api_field_render')): 
    function jobster_api_field_render() { 
        $options = get_option('jobster_apis_settings');

        $types = array(
            'none' => __('None', 'jobster'),
            'careerjet' => __('Careerjet', 'jobster'),
        ); ?>

        <select
            name="jobster_apis_settings[jobster_api_field]" 
            id="jobster_apis_settings[jobster_api_field]"
        >
            <?php foreach ($types as $type_key => $type_value) { ?>
                <option 
                    value="<?php echo esc_attr($type_key); ?>" 
                    <?php selected(
                        isset($options['jobster_api_field'])
                        && $options['jobster_api_field'] == $type_key
                    ) ?>
                >
                    <?php echo esc_html($type_value); ?>
                </option>
            <?php } ?>
        </select>
    <?php }
endif;

if (!function_exists('jobster_api_careerjet_locale_field_render')): 
    function jobster_api_careerjet_locale_field_render() { 
        $options = get_option('jobster_apis_settings');

        $locales = array('' => __('Default', 'jobster'), 'cs_CZ' => 'Czech - Czech Republic','da_DK' => 'Danish - Denmark','de_AT' => 'German - Austria','de_CH' => 'German - Switzerland','de_DE' => 'German - Germany','en_AE' => 'English - United Arab Emirates','en_AU' => 'English - Australia','en_CA' => 'English - Canada','en_CN' => 'English - China','en_HK' => 'English - Hong Kong','en_IE' => 'English - Ireland','en_IN' => 'English - India','en_MY' => 'English - Malaysia','en_NZ' => 'English - New Zealand','en_OM' => 'English - Oman','en_PH' => 'English - Philippines','en_PK' => 'English - Pakistan','en_QA' => 'English - Qatar','en_SG' => 'English - Singapore','en_GB' => 'English - United Kingdom','en_US' => 'English - United States','en_ZA' => 'English - South Africa','en_TW' => 'English - Taiwan','en_VN' => 'English - Vietnam','es_AR' => 'Spanish - Argentina','es_BO' => 'Spanish - Bolivia','es_CL' => 'Spanish - Chile','es_CR' => 'Spanish - Costa Rica','es_DO' => 'Spanish - Dominican Republic','es_EC' => 'Spanish - Ecuador','es_ES' => 'Spanish - Spain','es_GT' => 'Spanish - Guatemala','es_MX' => 'Spanish - Mexico','es_PA' => 'Spanish - Panama','es_PE' => 'Spanish - Peru','es_PR' => 'Spanish - Puerto Rico','es_PY' => 'Spanish - Paraguay','es_UY' => 'Spanish - Uruguay','es_VE' => 'Spanish - Venezuela','fi_FI' => 'Finnish - Finland','fr_CA' => 'French - Canada','fr_BE' => 'French - Belgium','fr_CH' => 'French - Switzerland','fr_FR' => 'French - France','fr_LU' => 'French - Luxembourg','fr_MA' => 'French - Morocco','hu_HU' => 'Hungarian - Hungary','it_IT' => 'Italian - Italy','ja_JP' => 'Japanese - Japan','ko_KR' => 'Korean - Korea','nl_BE' => 'Dutch - Belgium','nl_NL' => 'Dutch - Netherlands','no_NO' => 'Norwegian - Norway','pl_PL' => 'Polish - Poland','pt_PT' => 'Portuguese - Portugal','pt_BR' => 'Portuguese - Brazil','ru_RU' => 'Russian - Russia','ru_UA' => 'Russian - Ukraine','sv_SE' => 'Swedish - Sweden','sk_SK' => 'Slovak - Slovakia','tr_TR' => 'Turkish - Turkey','uk_UA' => 'Ukrainian - Ukraine','vi_VN' => 'Vietnamese - Vietnam','zh_CN' => 'Chinese - China'); ?>

        <select
            name="jobster_apis_settings[jobster_api_careerjet_locale_field]" 
            id="jobster_apis_settings[jobster_api_careerjet_locale_field]"
        >
            <?php foreach ($locales as $locale_k => $locale_v) { ?>
                <option 
                    value="<?php echo esc_attr($locale_k); ?>" 
                    <?php selected(
                        isset($options['jobster_api_careerjet_locale_field'])
                        && $options['jobster_api_careerjet_locale_field'] == $locale_k
                    ) ?>
                >
                    <?php echo esc_html($locale_v); ?>
                </option>
            <?php } ?>
        </select>
    <?php }
endif;

if (!function_exists('jobster_api_careerjet_affid_field_render')): 
    function jobster_api_careerjet_affid_field_render() {
        $options = get_option('jobster_apis_settings'); ?>

        <input 
            name="jobster_apis_settings[jobster_api_careerjet_affid_field]" 
            id="jobster_apis_settings[jobster_api_careerjet_affid_field]" 
            type="text" 
            size="40" 
            value="<?php if (isset($options['jobster_api_careerjet_affid_field'])) {
                echo esc_attr($options['jobster_api_careerjet_affid_field']);
            } ?>"
        >
    <?php }
endif;
?>