# Static authentication for TYPO3 backend
**THIS EXTENSION SHOULD ONLY BE USED IN DEVELOPMENT CONTEXT!**

It provides an admin backend login without any login data. An admin account will 
automatically created during login, if not exist.

## Motivation
Companies especially agencies needs to manage the login data even for development usage.
To help developers during their work, this extension provides an easy way to login to the
backend with an admin account. Just click on login and let the syste mdo the rest.   

## Installation
This extension can currently only be installed by composer.

```
composer require codemonkey1988/be-static-auth --dev
```

## Configuration

This extension needs no configuration. But if you want to, you can change the username from
the auto-created backend user.

**Username**
Enter a username that will be used to create a new backend user and logging in using it. 

## Found an issue?

You can create new issues at https://github.com/codemonkey1988/be-static-auth/issues.<br>
If you found a **security issue** please contact me personally using one of the following methods:
* Twitter: [@codemonkey1988](https://twitter.com/Codemonkey1988)
* TYPO3 Slack: timschreiner
* Email: [dev@tim-schreiner.de](dev@tim-schreiner.de)
