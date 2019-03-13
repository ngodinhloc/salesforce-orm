# Salesforce ORM

## Sample usage
<pre>
$config = [
            'clientId' => 'yourSalesforceClientId'
            'clientSecret' => "yourSalesforceSecret"
            'path' => 'yourSalesforcePaht'
            'username' => 'yourSalesforceUsername'
            'password' => 'yourSalesforcePassword'
            'apiVersion' => 'yourSalesforceApiVersion'
          ];
$entityManager = new \Salesforce\ORM\EntityManager(config);
$accountRepository = $entityManager->createRepository(Account::class);
/* @var Account $account */
$account = $accountRepository->find('0010p000002Wam9AAC');
</pre>
