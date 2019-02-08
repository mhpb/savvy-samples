For those of you who have been around since PayBear.io, you will need to upgrade your integration. With the release of Savvy and API v3, we will require users to upgrade both your account and your integrations.

To avoid service interruption, **this change has to be done by March 31, 2019**. 

**Important!** BEFORE UPGRADING YOUR INTEGRATION TO V3, YOU MUST CREATE A MERCHANT WALLET AT [www.savvy.io](https://www.savvy.io). Feel free to use your existing PayBear email to log in.

## Shopping Cart Integrations
For shopping cart integrations, this is a simple process.

1. Log in to your dashboard at https://www.savvy.io and create a merchant wallet when asked.
2. Download the new [plugin](https://github.com/savvytechcom) and follow the installation instructions.
3. Once the new plugin is installed and working, you can disable/delete the PayBear plugin.
## Custom Integrations
For custom integrations, the steps will depend on your integration. Here’s the general approach that you might take:

1. Log in to your dashboard at https://www.savvy.io and create or import a wallet.
2. Update your code to use new endpoints:

| Old Endpoint Path  | New Endpoint Path |
| ------------- | ------------- |
| https://api.paybear.io/v2  | https://api.savvy.io/v3  |
| https://test.paybear.io/v2  | https://api.test.savvy.io/v3  |

3. Payment addresses created with the old API will be monitored for at least 3 months. Addresses created with the new API will be monitored only for 24 hours by default. To override this behavior you may use a new `lock_address_timeout` parameter when creating payment addresses. The value in seconds determine how long the address will be locked to the invoice. Use -1 for maximum time (3 months). More information can be found in the API Docs.

## Testnet
Most users want to test their integration is working before going live to ensure your integration is set up and working properly, so we’ve added easy access to this via our testnet API. It works against blockchain test networks, and all the blockchains we support can be tested against. To get test coins you can use publicly available faucets.

