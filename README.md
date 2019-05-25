Very easy Monolog Injector into your class

## Usage

Download and include the Bundle class in your code, or install it via Composer.


Configration example :

	monolog_injection:
	  #You can use namespaces to set visibility of this bundle 
      namespaces:
        - AppBundle


Usage example :
    
	/**
     * @RaketmanLogger("validation")
     */
    class V1 extends BaseType
    {
        use RaketmanLoggerTrait;
		
Now you have property logger that contains monolog logger with channel "validation" (if exists) 

	 $this->logger->debug("validation-v1", ["error" => "some-error"]);

	
## Where to use

This is usefull for debug class without service definition, like FormBuilder, Entity and etc.