<?php
namespace PrimTools;

use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use PrimTools\Provider\ConsoleServiceProvider;
use PrimTools\Provider\DispatcherServiceProvider;
use PrimTools\Provider\MigrationServiceProvider;
use PrimTools\Provider\PackServiceProvider;


/**
 * The Cilex framework class.
 *
 * @author Jason Vaillancourt <j@masterj.net>
 *
 * @api
 */
class Application extends Container
{
    /**
     * @var ServiceProviderInterface[]
     */
    private $providers = [];

    /**
     * @var boolean
     */
    private $booted = false;

    /**
     * Registers the autoloader and necessary components.
     */
    public function __construct(string $name, $version = null, array $values = [])
    {
        parent::__construct($values);

        $this->register(new DispatcherServiceProvider);
        $this->register(
            new ConsoleServiceProvider,
            [
                'console.name'    => $name,
                'console.version' => $version,
            ]
        );
        $this->register(new MigrationServiceProvider);
        $this->register(new PackServiceProvider);
    }

    /**
     * {@inheritDoc}
     */
    public function register(ServiceProviderInterface $provider, array $values = array())
    {
        parent::register($provider, $values);

        $this->providers[] = $provider;
    }

    /**
     * Boots the Application by calling boot on every provider added and then subscribe
     * in order to add listeners.
     */
    public function boot()
    {
        if ($this->booted) {
            return;
        }

        $this->booted = true;

        foreach ($this->providers as $provider) {
            if ($provider instanceof EventListenerProviderInterface) {
                $provider->subscribe($this, $this['dispatcher']);
            }
        }
    }

    /**
     * Executes this application.
     */
    public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        $this->boot();

        return $this['console']->run($input, $output);
    }

    /**
     * Allows you to add a command as Command object or as a command name+callable
     */
    public function command($nameOrCommand, $callable = null)
    {
        if ($nameOrCommand instanceof Command) {
            $command = $nameOrCommand;
        } else {
            if (!is_callable($callable)) {
                throw new \InvalidArgumentException('$callable must be a valid callable with the command\'s code');
            }

            $command = new Command($nameOrCommand);
            $command->setCode($callable);
        }

        $this['console']->add($command);

        return $command;
    }
}
