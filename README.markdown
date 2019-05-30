
Very easy Monolog Injector into your class

## Usage

Download and include the Bundle class in your code, or install it via Composer.


Configration example :

	monolog_injection:
      directories:
        - %kernel.roor_dir%.'/../src'


Usage example :
    
	/**
     * @RaketmanLogger("validation")
     */
    class ValidationV1 extends BaseType
    {
        use RaketmanLoggerTrait;
		
Now you have property logger that contains monolog logger with channel "validation" (if exists) 

	 $this->logger->debug("validate-some-form", ["error" => "some-error"]);

	
## Where to use

This is usefull for debug class without service definition, like FormBuilder, Entity and etc.