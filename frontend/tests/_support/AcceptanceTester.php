<?php
namespace frontend\tests;

use Facebook\WebDriver\Exception\StaleElementReferenceException;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    const max_amount_of_tries = 10;

    /**
     * Define custom actions here
     */

    /**
     * Выполняет $I->waitForElementChange до тех пор, пока он не выполнится.
     * Проблема в том, что иногда эта сволочь выдаёт StaleElementReferenceException ('элемент не
     * найден в DOM'), а всё потому, что колбэк хэндлится раз в секунду, и иногда элементы
     * физически не успевают засеттиться в DOM.
     *
     * @param string   $selector
     * @param callable $callback
     * @param int|null $timeout
     */
    public function tryWaitForElementChange (string $selector, callable $callback, int $timeout = null)
    {
        $norm = false;

        while (!$norm) {
            try {
                $this->waitForElementChange($selector, $callback, $timeout);
                $norm = true;
            } catch (StaleElementReferenceException $e) {
            }
        }
    }

    /**
     * Выполняет $I->waitForElement до тех пор, пока эта сволочь не выполнится.
     *
     * @see \frontend\tests\AcceptanceTester::tryWaitForElementChange
     *
     * @param      $selector
     * @param null $timeout
     */
    public function tryWaitForElement ($selector, $timeout = null)
    {
        $norm = false;

        while (!$norm) {
            try {
                $this->waitForElement($selector, $timeout);
                $norm = true;
            } catch (StaleElementReferenceException $e) {
            }
        }
    }

    /**
     * Выполняет $I->waitForElementVisible до тех пор, пока эта сволочь не выполнится.
     *
     * @see \frontend\tests\AcceptanceTester::tryWaitForElementChange
     *
     * @param      $selector
     * @param null $timeout
     */
    public function tryWaitForElementVisible ($selector, $timeout = null)
    {
        $norm = false;

        while (!$norm) {
            try {
                $this->waitForElementVisible($selector, $timeout);
                $norm = true;
            } catch (StaleElementReferenceException $e) {
            }
        }
    }

    /**
     * Выполняет $I->selectOption до тех пор, пока эта сволочь не выполнится.
     *
     * @see \frontend\tests\AcceptanceTester::tryWaitForElementChange
     *
     * @param string $select
     * @param string $option
     *
     * @throws \Exception
     */
    public function trySelectOption (string $select, string $option)
    {
        $norm = false;

        $tryCount = 0;

        while (!$norm) {
            try {
                $this->selectOption($select, $option);
                $norm = true;
            } catch (StaleElementReferenceException $e) {
                $tryCount++;
                if ($tryCount > static::max_amount_of_tries) {
                    throw new \Exception('Max amount of tries reached during select option.');
                }
            }
        }
    }
}
