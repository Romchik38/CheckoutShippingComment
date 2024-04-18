<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Customer\Controller\Address;

use Romchik38\CheckoutShippingComment\Plugin\Customer\Controller\Address\FormPost as Plugin;
use Magento\Framework\Message\Manager;
use Magento\Framework\App\Request\Http;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerRepository;
use \Psr\Log\LoggerInterface\Proxy;
use Magento\Customer\Model\ResourceModel\AddressRepository;
use \Magento\Customer\Model\Session;
use \Magento\Framework\Api\SearchCriteriaBuilder;
use \Magento\Framework\Api\SortOrder;
use \Magento\Framework\Api\SortOrderFactory;
use \Magento\Customer\Controller\Address\FormPost as Subject;
use \Magento\Framework\Controller\Result\Redirect as Result;
use \Magento\Framework\Message\Collection;

class FormPostTest extends \PHPUnit\Framework\TestCase
{
    private $messageManager;
    private $request;
    private $shippingCommentCustomerRepository;
    private $logger;
    private $addressRepository;
    private $customerSession;
    private $searchCriteriaBuilder;
    private $sortOrderFactory;
    private $sortOrder;
    private $subject;
    private $result;
    private $messages;

    public function setUp(): void
    {
        $this->messageManager = $this->createMock(Manager::class);
        $this->request = $this->createMock(Http::class);
        $this->shippingCommentCustomerRepository = $this->createMock(ShippingCommentCustomerRepository::class);
        $this->logger = $this->createMock(Proxy::class);
        $this->addressRepository = $this->createMock(AddressRepository::class);
        $this->customerSession = $this->createMock(Session::class);
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->sortOrderFactory = $this->createMock(SortOrderFactory::class);
        $this->sortOrder = $this->createMock(SortOrder::class);
        $this->subject = $this->createMock(Subject::class);
        $this->result = $this->createMock(Result::class);
        $this->messages = $this->createMock(Collection::class);
    }

    protected function createNewPlugin()
    {
        return (
            new Plugin(
                $this->messageManager,
                $this->request,
                $this->shippingCommentCustomerRepository,
                $this->logger,
                $this->addressRepository,
                $this->customerSession,
                $this->searchCriteriaBuilder,
                $this->sortOrderFactory,
            )
        );
    }

    public function testAfterExecuteNotSuccess()
    {
        $plugin = $this->createNewPlugin();

        $this->messageManager->expects($this->once())->method('getMessages')
            ->willReturn($this->messages);
        
        $this->messages->expects($this->once())->method('getItems')->willReturn([]);

        $result = $plugin->afterExecute($this->subject, $this->result);
        $this->assertSame($this->result, $result);
    }
}
