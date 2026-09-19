import os

path = r"d:\Documents\WORK\VOYAGES EVENT\SISTEM DOORPRIZE\doorprize_voyages\resources\views\welcome.blade.php"
with open(path, 'r', encoding='utf-8') as f:
    lines = f.readlines()

header_lines = lines[3:26]
sidebar_lines = lines[31:115]
navbar_lines = lines[119:156]
footer_lines = lines[726:739]

base_path = r"d:\Documents\WORK\VOYAGES EVENT\SISTEM DOORPRIZE\doorprize_voyages\resources\views\components\\"

with open(base_path + 'header.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(header_lines)
with open(base_path + 'sidebar.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(sidebar_lines)
with open(base_path + 'navbar.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(navbar_lines)
with open(base_path + 'footer.blade.php', 'w', encoding='utf-8') as f:
    f.writelines(footer_lines)

new_lines = []
for i, line in enumerate(lines):
    if i == 3:
        new_lines.append("    @include('components.header')\n")
    elif 3 < i < 26:
        pass
    elif i == 31:
        new_lines.append("      @include('components.sidebar')\n")
    elif 31 < i < 115:
        pass
    elif i == 119:
        new_lines.append("        @include('components.navbar')\n")
    elif 119 < i < 156:
        pass
    elif i == 726:
        new_lines.append("        @include('components.footer')\n")
    elif 726 < i < 739:
        pass
    else:
        new_lines.append(line)

with open(path, 'w', encoding='utf-8') as f:
    f.writelines(new_lines)
print("Done")
