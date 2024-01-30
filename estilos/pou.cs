using System;

class Program {
    static void Main() {
        Console.Write("Ingrese un número entre 1 y 10: ");
        int numero = ObtenerNumero();

        if (numero >= 1 && numero <= 10) {
            string[] romanos = { "", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X" };
            Console.WriteLine($"Equivalente en romano: {romanos[numero]}");
        } else {
            Console.WriteLine("Número fuera del rango permitido.");
        }
    }

    static int ObtenerNumero() {
        int numero;
        while (!int.TryParse(Console.ReadLine(), out numero) || numero < 1 || numero > 10) {
            Console.Write("Ingrese un número válido entre 1 y 10: ");
        }
        return numero;
    }
}
