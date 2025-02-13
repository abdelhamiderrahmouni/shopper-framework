<?php

declare(strict_types=1);

return [

    'empty_country_selector' => 'Please select a country',
    'logo_description' => 'The logo of your store that will be visible on your site. This assets will appear on your invoices.',
    'about_description' => 'You can view this information on the About page on your website.',
    'currency_description' => 'This is the currency your products are sold in. After your first sale, currency is locked in and can’t be changed.',
    'mapbox_disabled' => 'Mapbox has not been activated.',

    'initialization' => [
        'step_one_title' => 'Store information',
        'step_one_description' => 'Provide useful information for your store.',
        'step_two_title' => 'Address Information',
        'step_two_description' => 'Provide store address information.',
        'step_tree_title' => 'Social Links (Optional)',
        'step_tree_description' => 'Links to your social media accounts.',

        'step' => 'Step :step of 3',
        'shop_configuration' => 'Shop configuration',
        'step_1' => 'Step 1 - Shop information',
        'tell_about' => 'Tell us about your Shop',
        'step_1_description' => 'This information will be useful if you want users of your site to directly contact you by email or by your phone number.',

        'step_2' => 'Step 2 - Address information',
        'address_description' => 'You must specify address and location of your shop',
        'step_2_description' => 'Don\'t Worry. You can change these setting at any time. Shopper allows you to start with the smallest level so that you can see the evolution of your shop.',

        'step_3' => 'Step 3 - Social links',
        'social_description' => 'Your shop on social networks.',
        'step_3_description' => 'You can add links to your social media accounts so that your shop can be found easily on your social media pages.',
        'action' => 'Setup my store',
    ],

    'settings' => [
        'title' => 'Store Setting',
        'store_details' => 'Store details',
        'store_detail_summary' => 'Your customers will use this information to contact you.',
        'email_helper' => 'Your customers will use this address if they need to contact you.',
        'phone_number_helper' => 'Your customers will use this phone number if they need to call you directly.',
        'assets' => 'Assets',
        'assets_summary' => 'The logo and cover image of your store that will be visible on your site. This assets will appear on your invoices.',
        'store_address' => 'Store address',
        'store_address_summary' => 'This address will appear on your invoices. You can edit the address used.',
        'store_currency' => 'Store currency',
        'store_currency_summary' => 'This is the currency your products are sold in. After your first sale, currency is locked in and can’t be changed.',
        'social_links' => 'Social links',
        'social_links_summary' => 'Information about your different accounts on social networks. Users will be able to contact you directly on your official pages.',
        'update_information' => 'Update information',
    ],

    'payment' => [
        'title' => 'Payments Methods',
        'stripe_description' => 'Accept payments on your store using third-party providers such as Stripe.',
        'stripe_enabled' => 'Stripe is available for your store.',
        'stripe_disabled' => 'Stripe is not enabled.',
        'stripe_provider' => 'This provider allows you to integrate Stripe PHP into your store to allow your customers to make payments.',
        'stripe_about' => 'Learn more about Stripe Payment',
        'stripe_actions' => [
            'enable' => 'Enable Stripe Payment',
            'disable' => 'Disable Stripe Payment',
        ],
        'stripe_environment' => 'Stripe has two environments Sandbox and Live, make sure to use sandbox for testing before going live.',
        'stripe_dashboard' => 'API Keys can be grabbed from',

        'paypal_description' => 'Accept payments on your store using third-party providers such as Paypal.',
        'paypal_enabled' => 'Paypal is available for your store.',
        'paypal_disabled' => 'Paypal is not enabled.',
        'paypal_provider' => 'This provider allows you to integrate Paypal PHP into your store to allow your customers to make payments.',
        'paypal_about' => 'Learn more about Paypal Payment',
        'paypal_actions' => [
            'enable' => 'Enable Paypal Payment',
            'disable' => 'Disable Paypal Payment',
        ],
        'paypal_environment' => 'Paypal has two environments Sandbox and Live, make sure to use sandbox for testing before going live.',
        'paypal_dashboard' => 'API Keys can be grabbed from',

        'create_payment' => 'Create payment method',
        'no_method' => 'No payment methods found',
    ],

    'validations' => [
        'shop_name' => 'Store name is required',
        'country' => 'Country is required',
    ],

    'notifications' => [
        'email_config' => 'Your mail configurations have been correctly updated!',
        'stripe' => 'Your Stripe payments configuration have been correctly updated!',
        'stripe_enable' => 'You have successfully enabled Stripe payment for your store!',
        'stripe_disable' => 'You have successfully disabled Stripe payment for your store!',
        'paypal_enable' => 'You have successfully enabled Paypal payment for your store!',
        'paypal_disable' => 'You have successfully disabled Paypal payment for your store!',
        'paypal' => 'Your Paypal payments configuration have been correctly updated!',
    ],

    'roles_permissions' => [
        'title' => 'User Roles & Access Management',
        'header_title' => 'Administrators & roles',
        'role_available' => 'Administrator role available',
        'role_available_summary' => 'A role provides access to predefined menus and features so that depending on the assigned role and permissions an administrator can have access to what he needs.',
        'new_role' => 'Add new role',
        'admin_accounts' => 'Administrators accounts',
        'admin_accounts_summary' => 'These are the members who are already in your store with their associated roles. You can assign new roles to existing member here.',
        'add_admin' => 'Add administrator',
        'users_role' => 'Users & roles',
        'login_information' => 'Login information',
        'login_information_summary' => 'This information will be useful for the administrator to connect to the administration of Shopper.',
        'send_invite' => 'Send Invite',
        'send_invite_summary' => 'Send an invitation to this administrator by email with his login information.',
        'personal_information' => 'Personal Information',
        'personal_information_summary' => 'Information related to the admin profile.',
        'role_information' => 'Role Information',
        'role_information_summary' => 'Assign roles to this administrator who will limit the actions he can do.',
        'roles' => 'Roles',
        'permissions' => 'Permissions',
        'choose_role' => 'Choose a role for this admin',
        'create_permission' => 'Create permission',
        'role_alert_msg' => 'You are about to update the admin role, this could block your access to the dashboard.',
        'with_role_name' => 'with :name role',
        'permissions_in_role' => 'in :name role',
        'custom_permission' => 'Custom permission',
    ],

    'mailable' => [
        'title' => 'Mail Configuration ~ Templates ~ Mailable',
        'configuration' => 'Configuration',
        'configuration_summary' => 'Manage email global configuration, driver, host, port etc.',
        'templates' => 'Templates',
        'templates_summary' => 'Modify the mail templates that are sent to customers and administrators, manage email layouts.',
        'mailable_summary' => 'Create Laravel mailable class to use on your project to send email notifications.',
        'email_config' => 'Email Configuration',
        'account_config' => 'Account Configuration',
        'account_config_summary' => "Setup the account used to send email to customers or admins. Don't share these informations",
        'server_config' => 'Server Configuration',
        'server_config_summary' => 'Information related to the mail server you are going to use and the associated protocol. For more information',
        'new_mailable' => 'Create new Mailable',
        'mailable_placeholder' => 'Enter mailable name e.g Welcome User, WelcomeUser',
        'markdown_template' => 'Markdown Template',
        'markdown_template_helper' => 'Use markdown template.',
        'force_mailable' => 'Force mailable creation even if already exists.',
        'title_template' => 'Mail ~ Add Template',
        'add_template' => 'Add Template',
        'create_new_template' => 'Create new template',
    ],

    'location' => [
        'description' => 'Manage the places you stock inventory, fulfill orders, and sell products.',
        'count' => 'You’re using :count of 4 locations available.',
        'add' => 'Add location',
        'detail' => 'Details',
        'detail_summary' => 'Give this location a short name to make it easy to identify. You’ll see this name in areas like products.',
        'address' => 'Inventory address',
        'address_summary' => "Your inventory's complete information. Please put valide informations this can be accessible for your customers.",
        'set_default' => 'Set as default inventory',
        'set_default_summary' => 'Inventory at this location is available for sale online and will use as default',
        'is_default' => 'This is your default inventory. To change whether you fulfill online orders from this inventory, select another default inventory first.',
    ],

    'analytics' => [
        'google' => 'Google Analytics',
        'google_description' => 'Google Analytics allows you to track visitors to your site and generates reports that will help you with your marketing.',
        'gtag' => 'Google Tag Manager',
        'gtag_description' => 'Google Tag Manager allows marketing managers to easily add tags (Analytics, remarketing, etc.)',
        'pixel' => 'Facebook Pixel',
        'pixel_description' => 'Facebook Pixel helps you create ad campaigns to find new customers who are most like your buyers.',
        'no_json' => 'No json file added',
    ],

    'legal' => [
        'title' => 'Legal policy',
        'refund' => 'Refund policy',
        'privacy' => 'Privacy policy',
        'shipping' => 'Shipping policy',
        'terms_of_use' => 'Terms of use',
        'summary' => 'Define the :policy to which all users and consumers of the products in your store will be subject.',
    ],
];
