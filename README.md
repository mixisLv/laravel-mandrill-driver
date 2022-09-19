# Laravel 9+ Mandrill Driver & Webhooks handler

This package re-enables Mandrill driver functionality using the Mail facade in Laravel 9+.

## Install

To install the package in your project, you need to require the package via composer:

```sh
composer require mixisLv/laravel-mandrill-driver
```

## Configure

To use the Mandrill driver, set the `MAIL_MAILER` environment variable to "mandrill". Next, update the `config/services.php` configuration file to include the following options:

```php
'mandrill' => [
    'secret' => env('MANDRILL_SECRET'),
],
```
## Usage

### Send e-mail

https://laravel.com/docs/9.x/mail#generating-mailables

You can also add custom Mandrill headers to each email sent. https://laravel.com/docs/9.x/mail#customizing-the-symfony-message
```php
// @todo

use Symfony\Component\Mime\Email;
 
/**
 * Build the message.
 *
 * @return $this
 */
public function build()
{
    $this->view('emails.example');
 
    $this->withSymfonyMessage(function (Email $message) {
        $message->getHeaders()->addTextHeader(
            'Custom-Mailchimp-Header', 'Header Value' // @see https://mailchimp.com/developer/transactional/docs/smtp-integration/#customize-messages-with-smtp-headers

        );
    });
 
    return $this;
}


```
### Listening response
```php
// @todo
```

### Webhooks

Forked from [eventhomes/laravel-mandrillhooks](https://github.com/eventhomes/laravel-mandrillhooks)

1) Create a controller that extends MandrillWebhookController as follows. You can then handle any Mandrillapp webhook event.

```php
use mixisLv\LaravelMandrillDriver\MandrillWebhookController;

class MandrillController extends MandrillWebhookController {

    /**
     * Handle a hard bounced email
     *
     * @param $payload
     */
    public function handleHardBounce($payload)
    {
        $email = $payload['msg']['email'];
    }

    /**
     * Handle a rejected email
     *
     * @param $payload
     */
    public function handleReject($payload)
    {
        $email = $payload['msg']['email'];
    }
}
```

2) Create the route to handle the webhook. In your routes.php file add the following.

```php
Route::post('mandrill-webhook', ['as' => 'mandrill.webhook', 'uses' => 'MandrillController@handleWebHook']);
```

3) [Exclude your route from CSRF protection](https://laravel.com/docs/5.4/csrf#csrf-excluding-uris) so it will not fail.
4) Make sure you add your webhook in Mandrill to point to your route. You can do this here: https://mandrillapp.com/settings/webhooks

#### Webhook Events
[Webhook event types](https://mandrill.zendesk.com/hc/en-us/articles/205583217-Introduction-to-Webhooks#event-types):

Event type              | Method             | Description
------------            |------------        |---------------
Sent	                | handleSend()       | message has been sent successfully
Bounced	                | handleHardBounce() | message has hard bounced
Opened	                | hadleOpen()        | recipient opened a message; will only occur when open tracking is enabled
Marked As Spam	        | handleSpam()       | recipient marked a message as spam
Rejected	            | handleReject()     | message was rejected
Delayed	                | handleDeferral()   | message has been sent, but the receiving server has indicated mail is being delivered too quickly and Mandrill should slow down sending temporarily
Soft-Bounced	        | handleSoftBounce() | message has soft bounced
Clicked	                | handleClick()      | recipient clicked a link in a message; will only occur when click tracking is enabled
Recipient Unsubscribes  | handleUnsub()      | recipient unsubscribes
Rejection Blacklist Changes	| handleBlacklist()  | triggered when a Rejection Blacklist entry is added, changed, or removed
Rejection Whitelist Changes	| handleWhitelist()  | triggered when a Rejection Whitelist entry is added or removed


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information about recent changes.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email info@mixis.lv instead of using the issue tracker.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
