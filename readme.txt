=== API Key Manager ===
Contributors: Johannes Luger
Requires at least: 6.1.1
Tested up to: 6.2
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The API Key Manager plugin (Version 2.0) allows you to securely store and manage API keys, now with added encryption for better security. Once you've installed and activated the plugin, you can access it by navigating to the "API Key Manager" page in your WordPress dashboard.

On this page, you can add new API keys by providing a key name and its corresponding value. You can also view and edit existing API keys or delete them if necessary.
The plugin stores all API keys in the WordPress database and encrypts them to protect against unauthorized access.

In addition to the main functionality, the plugin also includes a custom JavaScript file that can be used to access the API keys from within other scripts.

The plugin is designed to be easy to use and provides a simple interface for managing API keys.
With the API Key Manager plugin, you can rest assured that your API keys are secure and well-organized, making it easier to manage your web services and integrate them into your WordPress site.

To use the API Key Manager plugin (Version 2.0), follow these steps:

1. Install the plugin by uploading the plugin files to the /wp-content/plugins/ directory, or by searching for it in the WordPress plugins library and installing it from there.

2. Activate the plugin.

3. Go to the API Key Manager page in the WordPress dashboard by clicking on "API Key Manager" in the left-hand menu.

4. To add a new API key, enter a name for the key and the corresponding value in the "Add API Key" section, then click "Add API Key".

5. You can now use the API key in your JavaScript code with the following format: var your_variable = decrypted.your_key_name;

Note that the your_key_name part of the format should match the name you entered for the API key in the "Add API Key" section of the plugin.

DISCLAIMER: Please be aware that the API Key Manager plugin is provided without any warranty or guarantee of any kind.
The use of this plugin is entirely at your own risk, and it is highly recommended to test any plugins on a secure testing environment before deploying them on a live website.
The developer of this plugin will not be held responsible for any damages or issues that may arise from the use of this plugin.
We make no representations or warranties of any kind concerning the safety, suitability, lack of viruses, inaccuracies, typographical errors, or other harmful components of this plugin.
You assume full responsibility for any losses or damages resulting from your use of the plugin.
We will not be liable for any damages of any kind arising from the use of this plugin, including, but not limited to, direct, indirect, incidental, punitive, and consequential damages.