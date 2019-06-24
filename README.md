# Salesforce ORM for PHP: Doctrine style entities, entity manager and repository

## Configuration
composer.json
<pre>
"require": {
        "brightecapital/salesforce-orm": "^1.4.2"
    }
</pre>

Run 
<pre>
composer require brightecapital/salesforce-orm
</pre>
## Sample code
#### EntityManager
<pre>
$config = [
            'clientId' => 'yourSalesforceClientId'
            'clientSecret' => "yourSalesforceSecret"
            'path' => 'yourSalesforcePath'
            'username' => 'yourSalesforceUsername'
            'password' => 'yourSalesforcePassword'
            'apiVersion' => 'yourSalesforceApiVersion'
          ];
$conn = new \Salesforce\Client\Connection($config);          
$entityManager = new \Salesforce\ORM\EntityManager($conn);
</pre>

#### Repository
<pre>
/* @var \Salesforce\ORM\Repository */
$accountRepository = $entityManager->getRepository(Account::class);
</pre>
<pre>
class AccountRepository extends Repository
{
    protected $className = Account::class;
}

$accountRepository = new AccountRepository($entityManager);
</pre>
#### Objects
<pre>
/* @var Account $account */
$account = $entityManager->getRepository(Account::class)->find('0010p000002Wam9AAC');
$account = $accountRepository->find('0010p000002Wam9AAC');
</pre>

<pre>
$account = new Account();
</pre>

#### Create|Update Object
<pre>
$account = new Account();
$account->setName('Your Name);
$account->setWebsite('YourWebsite);
$accountRepository->save($account); // this will create a new Account entity

$account = $entityManager->getRepository(Account::class)->find('0010p000002Wam9AAC');
$account->setWebsite('YourWebsite);
$account->setName('YourName');
$accountRepository->save($account); // this will update a the current Account entity
</pre>
#### Entity and Field
<pre>
/**
 * Salesforce Account
 *
 * @package Salesforce\Entity
 * @SF\Object(name="Account")
 */
class Account extends Entity
{
    /**
     * @var string
     * @SF\Field(name="Name")
     * @SF\Required(value=true)
     */
    protected $name;

    /**
     * @var string
     * @SF\Field(name="Website")
     */
    protected $website;
 }
</pre>
 + @SF\Object(name="Account"): indicate that this class is mapping to Salesforce Account object
 + @SF\Field(name="Name") : indicate that the property is mapping to filed 'Name' in Salesforce Account object
#### Relations, Required, Validations
<pre>
/**
 * Salesforce Account
 *
 * @package Salesforce\Entity
 * @SF\Object(name="Account")
 */
class Account extends Entity
{
    /**
     * @var string
     * @SF\Field(name="Name")
     * @SF\Required(value=true)
     */
    protected $name;

    /**
     * @var string
     * @SF\Field(name="Website")
     * @SF\Url(value=true)
     */
    protected $website;
    
    /**
      * @var array
      * @SF\OneToMany(name="Contacts", targetClass="App\Domain\Marketing\Salesforce\Entity\Contact", field="Id", targetField="AccountId", lazy=false)
      */
     protected $contacts;

 }
</pre>

+ @SF\OneToMany(name="Contacts", targetClass="App\Domain\Marketing\Salesforce\Entity\Contact", field="Id", targetField="AccountId", lazy=false): indicate that one Account has many Contact
+ targetClass : the implemented class of Contact
+ field: the field/column of Account object
+ targetField: the field/column of the target Contact object
+ lazy: if lazy = false, the repository will autoload list of Contact of the Account when you do (default = true)
<pre>
 $account = $accountRepository->find('0010p000002Wam9AAC');
</pre>

+ @SF\Required(value=true): indicate that this field is required. An exception will be thrown if this property is not set when saving the entity
+ @SF\Url(value=true): indicate that this field is a url. An exception will be thrown if the value of this property is not an url

Available validations: Url, Email, Date
#### Find and Count
<pre>
// Find Account by conditions, by default lazy loading = false (will load relations)
$accounts = $accountRepo->findBy(['Company_Name__c = Adant Services Group Pty Ltd']);
// Find all Account, by default lazy loading = true (will not load relations)
$accounts = $accountRepo->findAll();
// Find total number of Account
$count = $accountRepository->count();
</pre>
