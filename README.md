# Salesforce ORM

## Configuration
composer.json
<pre>
"require": {
        "brightecapital/salesforce-orm": "^1.1"
    },
    "repositories": [
        {
            "url": "https://github.com/brightecapital/salesforce-orm.git",
            "type": "git"
        }
    ],
</pre>

Run 
<pre>
composer require brightecapital/salesforce-orm
</pre>
## Sample usage
<pre>
$config = [
            'clientId' => 'yourSalesforceClientId'
            'clientSecret' => "yourSalesforceSecret"
            'path' => 'yourSalesforcePath'
            'username' => 'yourSalesforceUsername'
            'password' => 'yourSalesforcePassword'
            'apiVersion' => 'yourSalesforceApiVersion'
          ];
$entityManager = new \Salesforce\ORM\EntityManager(config);
$accountRepository = $entityManager->createRepository(Account::class);
/* @var Account $account */
$account = $accountRepository->find('0010p000002Wam9AAC');
</pre>
