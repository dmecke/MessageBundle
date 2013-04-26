Installation
============

1. Add the following to your `composer.json` file:

    ```js
    // composer.json
    {
        // ...
        require: {
            // ...
            "cunningsoft/message-bundle": "0.1.*"
        }
    }
    ```

2. Run `composer update cunningsoft/message-bundle` to install the new dependencies.

3. Register the new bundle in your `AppKernel.php`:

    ```php
    <?php
    // in AppKernel::registerBundles()
    $bundles = array(
        // ...
        new Cunningsoft\MessageBundle\CunningsoftMessageBundle(),
        // ...
    );
    ```

4. Let your user entity implement the `Cunningsoft\MessageBundle\Entity\UserInterface`:

    ```php
    // Acme\ProjectBundle\Entity\User.php
    <?php

    namespace Acme\ProjectBundle\Entity;

    use Cunningsoft\MessageBundle\Entity\UserInterface as MessageUserInterface;

    class User implements MessageUserInterface
    {
        /**
         * @var int
         */
        protected $id;

        /**
         * @var string
         */
        protected $username;

        /**
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * @return string
         */
        public function getUsername()
        {
            return $this->username;
        }
        // ...
    ```

5. Map the interface to your user entity in your `config.yml`:

    ```yaml
    // app/config/config.yml
    // ...
    doctrine:
        orm:
            resolve_target_entities:
                Cunningsoft\MessageBundle\Entity\UserInterface: Acme\ProjectBundle\Entity\User
    ```

6. Update your database schema:

    ```bash
    $ app/console doctrine:schema:update
    ```

7. Import routes:

    ```yaml
    // app/config/routing.yml
    // ...
    cunningsoft_message_bundle:
        resource: "@CunningsoftMessageBundle/Controller"
        type: annotation
    ```

8. Link to the messages list:

    ```twig
    // src/Acme/ProjectBundle/Resources/views/Default/index.html.twig
    // ...
    <a href="{{ path('cunningsoft_message_list') }}">messages</a>
    // ...
    ```

11. Create a child bundle to fetch users

    ```
    mkdir src/Acme/MessageBundle
    ```

    ```php
    // src/Acme/MessageBundle/AcmeMessageBundle.php
    <?php

    namespace Acme\MessageBundle;

    use Symfony\Component\HttpKernel\Bundle\Bundle;

    class AcmeMessageBundle extends Bundle
    {
        public function getParent()
        {
            return 'CunningsoftMessageBundle';
        }
    }
    ```

    ```php
    // src/Acme/MessageBundle/Controller/MessageController.php
    <?php

    namespace Acme\MessageBundle\Controller;

    use Cunningsoft\MessageBundle\Controller\MessageController as BaseController;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

    /**
     * @Route("/message")
     */
    class MessageController extends BaseController
    {
        public function findUser($id)
        {
            return $this->get('doctrine.orm.entity_manager')->getRepository('AcmeProjectBundle:User')->find($id);
        }

        public function findAllUsers()
        {
            return $this->get('doctrine.orm.entity_manager')->getRepository('AcmeProjectBundle:User')->findAll();
        }
    }
    ```

Changelog
=========

* 0.1 (master)
First working version.
