<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

/**
 * Route测试
 * @author    liu21st <liu21st@gmail.com>
 */

namespace tests\thinkphp\library\think;

use think\Config;
use think\Request;
use think\Route;

class routeTest extends \PHPUnit_Framework_TestCase
{

    protected function setUp()
    {
        Config::set('app_multi_module', true);
    }

    public function testRegister()
    {
        $request = Request::instance();
        Route::get('hello/:name', 'Index/hello');
        Route::get(['hello/:name' => 'Index/hello']);
        Route::post('hello/:name', 'Index/post');
        Route::put('hello/:name', 'Index/put');
        Route::delete('hello/:name', 'Index/delete');
        Route::patch('hello/:name', 'Index/patch');
        Route::any('user/:id', 'Index/user');
        $result = Route::check($request, 'hello/thinkphp');
        $this->assertEquals([null, 'Index', 'hello'], $result['module']);
        $this->assertEquals(['hello' => true, 'user/:id' => true, 'hello/:name' => ['rule' => 'hello/:name', 'route' => 'Index/hello', 'var' => ['name' => 1], 'option' => [], 'pattern' => []]], Route::rules('GET'));
        Route::rule('type1/:name', 'Index/type', 'PUT|POST');
        Route::rule(['type2/:name' => 'Index/type1']);
        Route::rule([['type3/:name', 'Index/type2', ['method' => 'POST']]]);
        Route::rule(['name', 'type4/:name'], 'Index/type4');
    }

    public function testImport()
    {
        $rule = [
            '__domain__' => ['subdomain2.thinkphp.cn' => 'blog1'],
            '__alias__'  => ['blog1' => 'blog1'],
            '__rest__'   => ['res' => ['Index/blog']],
            'bbb'        => ['Index/blog1', ['method' => 'POST']],
            'ddd'        => '',
            ['hello1/:ddd', 'Index/hello1', ['method' => 'POST']],
        ];
        Route::import($rule);
    }

    public function testResource()
    {
        $request = Request::instance();
        Route::resource('res', 'Index/blog');
        Route::resource(['res' => ['Index/blog']]);
        $result = Route::check($request, 'res');
        $this->assertEquals(['Index', 'blog', 'Index'], $result['module']);
        $result = Route::check($request, 'res/create');
        $this->assertEquals(['Index', 'blog', 'create'], $result['module']);
        $result = Route::check($request, 'res/8');
        $this->assertEquals(['Index', 'blog', 'read'], $result['module']);
        $result = Route::check($request, 'res/8/edit');
        $this->assertEquals(['Index', 'blog', 'edit'], $result['module']);

        Route::resource('blog.comment', 'Index/comment');
        $result = Route::check($request, 'blog/8/comment/10');
        $this->assertEquals(['Index', 'comment', 'read'], $result['module']);
        $result = Route::check($request, 'blog/8/comment/10/edit');
        $this->assertEquals(['Index', 'comment', 'edit'], $result['module']);

    }

    public function testRest()
    {
        $request = Request::instance();
        Route::rest('read', ['GET', '/:id', 'look']);
        Route::rest('create', ['GET', '/create', 'add']);
        Route::rest(['read' => ['GET', '/:id', 'look'], 'create' => ['GET', '/create', 'add']]);
        Route::resource('res', 'Index/blog');
        $result = Route::check($request, 'res/create');
        $this->assertEquals(['Index', 'blog', 'add'], $result['module']);
        $result = Route::check($request, 'res/8');
        $this->assertEquals(['Index', 'blog', 'look'], $result['module']);

    }

    public function testMixVar()
    {
        $request = Request::instance();
        Route::get('hello-<name>', 'Index/hello', [], ['name' => '\w+']);
        $result = Route::check($request, 'hello-thinkphp');
        $this->assertEquals([null, 'Index', 'hello'], $result['module']);
        Route::get('hello-<name><id?>', 'Index/hello', [], ['name' => '\w+', 'id' => '\d+']);
        $result = Route::check($request, 'hello-thinkphp2016');
        $this->assertEquals([null, 'Index', 'hello'], $result['module']);
        Route::get('hello-<name>/[:id]', 'Index/hello', [], ['name' => '\w+', 'id' => '\d+']);
        $result = Route::check($request, 'hello-thinkphp/2016');
        $this->assertEquals([null, 'Index', 'hello'], $result['module']);
    }

    public function testParseUrl()
    {
        $result = Route::parseUrl('hello');
        $this->assertEquals(['hello', null, null], $result['module']);
        $result = Route::parseUrl('Index/hello');
        $this->assertEquals(['Index', 'hello', null], $result['module']);
        $result = Route::parseUrl('Index/hello?name=thinkphp');
        $this->assertEquals(['Index', 'hello', null], $result['module']);
        $result = Route::parseUrl('Index/user/hello');
        $this->assertEquals(['Index', 'user', 'hello'], $result['module']);
        $result = Route::parseUrl('Index/user/hello/name/thinkphp');
        $this->assertEquals(['Index', 'user', 'hello'], $result['module']);
        $result = Route::parseUrl('Index-Index-hello', '-');
        $this->assertEquals(['Index', 'Index', 'hello'], $result['module']);
    }

    public function testCheckRoute()
    {
        Route::get('hello/:name', 'Index/hello');
        Route::get('blog/:id', 'blog/read', [], ['id' => '\d+']);
        $request = Request::instance();
        $this->assertEquals(false, Route::check($request, 'test/thinkphp'));
        $this->assertEquals(false, Route::check($request, 'blog/thinkphp'));
        $result = Route::check($request, 'blog/5');
        $this->assertEquals([null, 'blog', 'read'], $result['module']);
        $result = Route::check($request, 'hello/thinkphp/abc/test');
        $this->assertEquals([null, 'Index', 'hello'], $result['module']);
    }

    public function testCheckRouteGroup()
    {
        $request = Request::instance();
        Route::pattern(['id' => '\d+']);
        Route::pattern('name', '\w{6,25}');
        Route::group('group', [':id' => 'Index/hello', ':name' => 'Index/say']);
        $this->assertEquals(false, Route::check($request, 'empty/think'));
        $result = Route::check($request, 'group/think');
        $this->assertEquals(false, $result['module']);
        $result = Route::check($request, 'group/10');
        $this->assertEquals([null, 'Index', 'hello'], $result['module']);
        $result = Route::check($request, 'group/thinkphp');
        $this->assertEquals([null, 'Index', 'say'], $result['module']);
        Route::group('group2', function () {
            Route::group('group3', [':id' => 'Index/hello', ':name' => 'Index/say']);
            Route::rule(':name', 'Index/hello');
            Route::auto('Index');
        });
        $result = Route::check($request, 'group2/thinkphp');
        $this->assertEquals([null, 'Index', 'hello'], $result['module']);
        $result = Route::check($request, 'group2/think');
        $this->assertEquals(['Index', 'group2', 'think'], $result['module']);
        $result = Route::check($request, 'group2/group3/thinkphp');
        $this->assertEquals([null, 'Index', 'say'], $result['module']);
        Route::group('group4', function () {
            Route::group('group3', [':id' => 'Index/hello', ':name' => 'Index/say']);
            Route::rule(':name', 'Index/hello');
            Route::miss('Index/__miss__');
        });
        $result = Route::check($request, 'group4/thinkphp');
        $this->assertEquals([null, 'Index', 'hello'], $result['module']);
        $result = Route::check($request, 'group4/think');
        $this->assertEquals([null, 'Index', '__miss__'], $result['module']);

        Route::group(['prefix' => 'prefix/'], function () {
            Route::rule('hello4/:name', 'hello');
        });
        Route::group(['prefix' => 'prefix/'], [
            'hello4/:name' => 'hello',
        ]);
        $result = Route::check($request, 'hello4/thinkphp');
        $this->assertEquals([null, 'prefix', 'hello'], $result['module']);
        Route::group('group5', [
            [':name', 'hello', ['method' => 'GET|POST']],
            ':id' => 'hello',
        ], ['prefix' => 'prefix/']);
        $result = Route::check($request, 'group5/thinkphp');
        $this->assertEquals([null, 'prefix', 'hello'], $result['module']);
    }

    public function testControllerRoute()
    {
        $request = Request::instance();
        Route::controller('controller', 'Index/Blog');
        $result = Route::check($request, 'controller/info');
        $this->assertEquals(['Index', 'Blog', 'getinfo'], $result['module']);
        Route::setMethodPrefix('GET', 'read');
        Route::setMethodPrefix(['get' => 'read']);
        Route::controller('controller', 'Index/Blog');
        $result = Route::check($request, 'controller/phone');
        $this->assertEquals(['Index', 'Blog', 'readphone'], $result['module']);
    }

    public function testAliasRoute()
    {
        $request = Request::instance();
        Route::alias('alias', 'Index/Alias');
        $result = Route::check($request, 'alias/info');
        $this->assertEquals('Index/Alias/info', $result['module']);
    }

    public function testRouteToModule()
    {
        $request = Request::instance();
        Route::get('hello/:name', 'Index/hello');
        Route::get('blog/:id', 'blog/read', [], ['id' => '\d+']);
        $this->assertEquals(false, Route::check($request, 'test/thinkphp'));
        $this->assertEquals(false, Route::check($request, 'blog/thinkphp'));
        $result = Route::check($request, 'hello/thinkphp');
        $this->assertEquals([null, 'Index', 'hello'], $result['module']);
        $result = Route::check($request, 'blog/5');
        $this->assertEquals([null, 'blog', 'read'], $result['module']);
    }

    public function testRouteToController()
    {
        $request = Request::instance();
        Route::get('say/:name', '@Index/hello');
        $this->assertEquals(['type' => 'controller', 'controller' => 'Index/hello', 'var' => []], Route::check($request, 'say/thinkphp'));
    }

    public function testRouteToMethod()
    {
        $request = Request::instance();
        Route::get('user/:name', '\app\index\service\User::get', [], ['name' => '\w+']);
        Route::get('info/:name', '\app\index\model\Info@getInfo', [], ['name' => '\w+']);
        $this->assertEquals(['type' => 'method', 'method' => '\app\index\service\User::get', 'var' => []], Route::check($request, 'user/thinkphp'));
        $this->assertEquals(['type' => 'method', 'method' => ['\app\index\model\Info', 'getInfo'], 'var' => []], Route::check($request, 'info/thinkphp'));
    }

    public function testRouteToRedirect()
    {
        $request = Request::instance();
        Route::get('art/:id', '/article/read/id/:id', [], ['id' => '\d+']);
        $this->assertEquals(['type' => 'redirect', 'url' => '/article/read/id/8', 'status' => 301], Route::check($request, 'art/8'));
    }

    public function testBind()
    {
        $request = Request::instance();
        Route::bind('Index/blog');
        Route::get('blog/:id', 'Index/blog/read');
        $result = Route::check($request, 'blog/10');
        $this->assertEquals(['Index', 'blog', 'read'], $result['module']);
        $result = Route::parseUrl('test');
        $this->assertEquals(['Index', 'blog', 'test'], $result['module']);

        Route::bind('\app\index\controller', 'namespace');
        $this->assertEquals(['type' => 'method', 'method' => ['\app\index\controller\Blog', 'read'], 'var' => []], Route::check($request, 'blog/read'));

        Route::bind('\app\index\controller\Blog', 'class');
        $this->assertEquals(['type' => 'method', 'method' => ['\app\index\controller\Blog', 'read'], 'var' => []], Route::check($request, 'read'));
    }

    public function testDomain()
    {
        $request = Request::create('http://subdomain.thinkphp.cn');
        Route::domain('subdomain.thinkphp.cn', 'sub?abc=test&status=1');
        $rules = Route::rules('GET');
        Route::checkDomain($request, $rules);
        $this->assertEquals('sub', Route::getbind('module'));
        $this->assertEquals('test', $_GET['abc']);
        $this->assertEquals(1, $_GET['status']);

        Route::domain('subdomain.thinkphp.cn', '\app\index\controller');
        $rules = Route::rules('GET');
        Route::checkDomain($request, $rules);
        $this->assertEquals('\app\index\controller', Route::getbind('namespace'));

        Route::domain(['subdomain.thinkphp.cn' => '@\app\index\controller\blog']);
        $rules = Route::rules('GET');
        Route::checkDomain($request, $rules);
        $this->assertEquals('\app\index\controller\blog', Route::getbind('class'));

    }
}
