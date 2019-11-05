// namespaces are used in packages generated for each language
namespace php adder
namespace py adder

// you can name your own types and rename the built-in ones.
typedef i32 int

service AddService {

// add method - returns result of sum of two integers
    int add(1:int number1, 2:int number2),

}
