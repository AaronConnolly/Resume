def canConstruct(ransomNote, magazine):
    """
    :type ransomNote: str
    :type magazine: str
    :rtype: bool
    """
    for i in range(len(ransomNote)):
        if ransomNote[i] in magazine:
            magazine=magazine.replace(ransomNote[i], " ", 1)
            print(magazine)
        else:
            return False
    return True

print(canConstruct("aa","ab"))
