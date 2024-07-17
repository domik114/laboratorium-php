import itertools
import random
import hashlib


def hash_passwords(passwords):
    hashed_passwords = []
    for password in passwords:
        sha256 = hashlib.sha256()
        password_bytes = password.encode('utf-8')
        sha256.update(password_bytes)
        hashed_password = sha256.hexdigest()
        hashed_passwords.append(hashed_password)
    return hashed_passwords

def modify_password(file_path='passwords.txt'):
    modified_passwords = set()
    special_chars = ['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '_', '+']
    years = [str(year) for year in range(1980, 2025)] + [str(year)[2:] for year in range(1980, 2025)]

    with open(file_path, 'r') as file:
        for password in file:
            password = password.strip()
            cases = [password.lower(), password.capitalize()]
            for case, special, year in itertools.product(cases, special_chars, years):
                new_password = f"{case}{special}{year}"
                modified_passwords.add(new_password)

    return list(modified_passwords)

modified_password_list = modify_password('passwords.txt')

with open('password_variations.txt', 'w') as file:
    for password in modified_password_list:
        file.write(f"{password}\n")

hash_passwords = hash_passwords(modified_password_list)

with open('password_hash.txt', 'w') as file:
    for password in hash_passwords:
        file.write(f"{password}\n")
