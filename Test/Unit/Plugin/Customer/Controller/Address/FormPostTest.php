<?php

declare(strict_types=1);

namespace Romchik38\CheckoutShippingComment\Test\Unit\Plugin\Customer\Controller\Address;

use Romchik38\CheckoutShippingComment\Plugin\Customer\Controller\Address\FormPost as Plugin;
use Magento\Framework\Message\Manager;
use Magento\Framework\App\Request\Http;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomerRepository;
use Romchik38\CheckoutShippingComment\Model\ShippingCommentCustomer;
use \Psr\Log\LoggerInterface\Proxy;
use Magento\Customer\Model\ResourceModel\AddressRepository;
use \Magento\Customer\Model\Session;
use \Magento\Customer\Model\Data\Address;
use \Magento\Customer\Api\Data\AddressExtension;
use \Magento\Framework\Api\SearchCriteriaBuilder;
use \Magento\Framework\Api\SearchCriteria;
use \Magento\Customer\Model\AddressSearchResults;
use \Magento\Framework\Api\SortOrder;
use \Magento\Framework\Api\SortOrderFactory;
use \Magento\Customer\Controller\Address\FormPost as Subject;
use \Magento\Framework\Controller\Result\Redirect as Result;
use \Magento\Framework\Message\Collection;
use \Magento\Framework\Message\Success;
use \Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

class FormPostTest extends \PHPUnit\Framework\TestCase
{
    private $messageManager;
    private $request;
    private $shippingCommentCustomerRepository;
    private $logger;
    private $addressRepository;
    private $customerSession;
    private $customerAddress;
    private $addressExtension;
    private $searchCriteriaBuilder;
    private $searchCriteria;
    private $addressSearchResults;
    private $sortOrderFactory;
    private $sortOrder;
    private $subject;
    private $result;
    private $messages;
    private $messageSuccess;

    public function setUp(): void
    {
        $this->messageManager = $this->createMock(Manager::class);
        $this->request = $this->createMock(Http::class);
        $this->shippingCommentCustomerRepository = $this->createMock(ShippingCommentCustomerRepository::class);
        $this->logger = $this->createMock(Proxy::class);
        $this->addressRepository = $this->createMock(AddressRepository::class);
        $this->customerSession = $this->createMock(Session::class);
        $this->customerAddress = $this->createMock(Address::class);
        $this->addressExtension = $this->createMock(AddressExtension::class);
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->searchCriteria = $this->createMock(SearchCriteria::class);
        $this->addressSearchResults = $this->createMock(AddressSearchResults::class);
        $this->sortOrderFactory = $this->createMock(SortOrderFactory::class);
        $this->sortOrder = $this->createMock(SortOrder::class);
        $this->subject = $this->createMock(Subject::class);
        $this->result = $this->createMock(Result::class);
        $this->messages = $this->createMock(Collection::class);
        $this->messageSuccess = $this->createMock(Success::class);
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

    protected function prepareSuccess()
    {
        $this->messageManager->expects($this->once())->method('getMessages')
            ->willReturn($this->messages);

        $this->messages->expects($this->once())->method('getItems')
            ->willReturn([$this->messageSuccess]);
    }

    protected function prepareCallbackGetParam($id, $comment)
    {
        return function ($param) use ($id, $comment) {
            $arr = ['id' => $id, 'comment' => $comment];
            return $arr[$param];
        };
    }

    protected function prepareToSaveNewAddress()
    {
        $customerId = 1;

        $this->prepareSuccess();

        $callbackGetParam = $this->prepareCallbackGetParam(null, 'some somment');

        $this->request->method('getParam')->willReturnCallback($callbackGetParam);

        $this->customerSession->method('getCustomerId')->willReturn($customerId);

        $this->sortOrderFactory->method('create')->willReturn($this->sortOrder);
        $this->sortOrder->method('setField')->willReturn($this->sortOrder);
        $this->sortOrder->method('setDirection')->willReturn($this->sortOrder);

        $this->searchCriteriaBuilder->method('create')->willReturn($this->searchCriteria);
        $this->addressRepository->method('getList')->willReturn($this->addressSearchResults);
    }

    /**
     * The plugin do nothing 
     * because app does't save a customer address
     * */
    public function testAfterExecuteNotSuccess()
    {
        $plugin = $this->createNewPlugin();

        $this->messageManager->expects($this->once())->method('getMessages')
            ->willReturn($this->messages);

        $this->messages->expects($this->once())->method('getItems')->willReturn([]);

        $result = $plugin->afterExecute($this->subject, $this->result);
        $this->assertSame($this->result, $result);
    }

    /**
     * The plugin do nothing 
     * because method saveNewAddress doesn't find a customer address for provided customer id
     */
    public function testAfterExecuteCreateNewAddressButAddressDoesntExist()
    {
        $plugin = $this->createNewPlugin();

        $this->prepareToSaveNewAddress();

        $this->addressSearchResults->method('getItems')->willReturn([]);
        $this->addressRepository->expects($this->never())->method('save');

        $result = $plugin->afterExecute($this->subject, $this->result);
        $this->assertSame($this->result, $result);
    }

    /**
     * The plugin add extension attributes to just created customer address and save it again
     */
    public function testAfterExecuteCreateNewAddressAndSaveIt()
    {
        $plugin = $this->createNewPlugin();

        $this->prepareToSaveNewAddress();

        $this->addressSearchResults->method('getItems')->willReturn([$this->customerAddress]);
        $this->customerAddress->method('getExtensionAttributes')->willReturn($this->addressExtension);
        $this->addressExtension->expects($this->once())->method('setCommentField')
            ->with($this->equalTo('some somment'));
        $this->addressRepository->expects($this->once())->method('save')
            ->with($this->equalTo($this->customerAddress));

        $result = $plugin->afterExecute($this->subject, $this->result);
        $this->assertSame($this->result, $result);
    }

    /**
     * The plugin add extension attributes to just created customer address and save it again
     * But method save will throw error
     */
    public function testAfterExecuteCreateNewAddressButSaveThrowError()
    {
        $plugin = $this->createNewPlugin();
        $this->prepareToSaveNewAddress();

        $this->addressSearchResults->method('getItems')->willReturn([$this->customerAddress]);
        $this->customerAddress->method('getExtensionAttributes')->willReturn($this->addressExtension);

        $this->addressRepository->expects($this->once())->method('save')
            ->willThrowException(new LocalizedException(__('')));

        $result = $plugin->afterExecute($this->subject, $this->result);
        $this->assertSame($this->result, $result);
    }

    /**
     * 2.1
     * The plugin do nothing, because comment wasn't provided
     */
    public function testAfterExecuteEditWithoutComment()
    {
        $plugin = $this->createNewPlugin();

        $this->prepareSuccess();
        $callbackGetParam = $this->prepareCallbackGetParam(1, null);
        $this->request->method('getParam')->willReturnCallback($callbackGetParam);

        $result = $plugin->afterExecute($this->subject, $this->result);
        $this->assertSame($this->result, $result);
    }

    /**
     * Plugin save comment
     */
    public function testAfterExecuteEditAndSave()
    {
        $plugin = $this->createNewPlugin();

        $this->prepareSuccess();
        $callbackGetParam = $this->prepareCallbackGetParam(1, 'some comment to save');
        $this->request->method('getParam')->willReturnCallback($callbackGetParam);

        $comment = $this->createMock(ShippingCommentCustomer::class);
        $this->shippingCommentCustomerRepository->method('getByCustomerAddressId')
            ->willReturn($comment);

        $comment->expects($this->once())->method('setComment')
            ->with($this->equalTo('some comment to save'));

        $this->shippingCommentCustomerRepository->expects($this->once())
            ->method('save')->with($this->callback(function ($param) use ($comment) {
                $this->assertSame($comment, $param);
                return true;
            }));

        $result = $plugin->afterExecute($this->subject, $this->result);
        $this->assertSame($this->result, $result);
    }

    /**
     * Plugin save a comment, but repository throw error
     */
    public function testAfterExecuteEditAndSaveButSaveThrowError()
    {
        $plugin = $this->createNewPlugin();

        $this->prepareSuccess();
        $callbackGetParam = $this->prepareCallbackGetParam(1, 'some comment to save');
        $this->request->method('getParam')->willReturnCallback($callbackGetParam);

        $comment = $this->createMock(ShippingCommentCustomer::class);
        $this->shippingCommentCustomerRepository->method('getByCustomerAddressId')
            ->willReturn($comment);

        $this->shippingCommentCustomerRepository->expects($this->once())
            ->method('save')->willThrowException(new CouldNotSaveException(__('')));

        $result = $plugin->afterExecute($this->subject, $this->result);
        $this->assertSame($this->result, $result);
    }

    /**
     * 2.3
     * Plugin create a comment for existing customer address 
     */
    public function testAfterExecuteEditAndCreateNewComment()
    {
        $plugin = $this->createNewPlugin();

        $this->prepareSuccess();
        $callbackGetParam = $this->prepareCallbackGetParam(1, 'some comment to save');
        $this->request->method('getParam')->willReturnCallback($callbackGetParam);

        $comment = $this->createMock(ShippingCommentCustomer::class);

        $this->shippingCommentCustomerRepository->expects($this->once())
            ->method('getByCustomerAddressId')
            ->willThrowException(new NoSuchEntityException(__('')));
        $this->shippingCommentCustomerRepository->method('create')->willReturn($comment);

        $comment->expects($this->once())->method('setComment')
            ->with($this->equalTo('some comment to save'));
        $comment->expects($this->once())->method('setCustomerAddressId')
            ->with($this->equalTo(1));

        $this->shippingCommentCustomerRepository->expects($this->once())
            ->method('save')->with($this->callback(function ($param) use ($comment) {
                $this->assertSame($comment, $param);
                return true;
            }));

        $result = $plugin->afterExecute($this->subject, $this->result);
        $this->assertSame($this->result, $result);
    }

    /**
     * 2.3
     * Plugin create a comment for existing customer address but repository save method
     * throw exception
     */
    public function testAfterExecuteEditAndCreateNewCommentButSaveThrowException()
    {
        $plugin = $this->createNewPlugin();

        $this->prepareSuccess();
        $callbackGetParam = $this->prepareCallbackGetParam(1, 'some comment to save');
        $this->request->method('getParam')->willReturnCallback($callbackGetParam);

        $comment = $this->createMock(ShippingCommentCustomer::class);

        $this->shippingCommentCustomerRepository->method('getByCustomerAddressId')
            ->willThrowException(new NoSuchEntityException(__('')));
        $this->shippingCommentCustomerRepository->method('create')->willReturn($comment);

        $this->shippingCommentCustomerRepository->expects($this->once())
            ->method('save')->willThrowException(new CouldNotSaveException(__('')));

        $result = $plugin->afterExecute($this->subject, $this->result);
        $this->assertSame($this->result, $result);
    }
}
