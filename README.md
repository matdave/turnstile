# Turnstile for MODX

Turnstile is a MODX Extra that allows you to incorporate CloudFlare's 
[Turnstile](https://www.cloudflare.com/products/turnstile/) Captcha into your MODX site.

## Installation

Install via Package Management.

Sign up for a CloudFlare account. Within the CloudFlare dashboard, click on Turnstile and 
select "Add Your Site". You can then copy the Site Key and Secret Key into the Turnstile
system settings in MODX.

## Usage

Turnstile currently only works with FormIt. To use it, add the following to your FormIt call:

```
[[!FormIt?
&hooks=`tsFormItHook`
...
```

Within the body of your form, add the following snippet:

```
[[!tsRender]]
```

This will render the Turnstile Captcha. If the user fails the Captcha, the form will not be
submitted.
