from subprocess import check_output, check_call
import os


def test(test, args=[]):
    working_dir = '/tmp/scriptsdev/' + test

    check_call(['rm', '-rf', working_dir])
    check_call(['mkdir', '-p', working_dir])

    source_composer_json = open('tests/%s.json' % test, 'r').read()
    source_composer_json = source_composer_json.replace('<PLUGIN_PATH>', os.getcwd())

    target_composer_json = open(working_dir + '/composer.json', 'w')
    target_composer_json.write(source_composer_json)
    target_composer_json.close()

    return check_output(['composer', 'update', '-v'] + args, cwd=working_dir)


def check(expect, actual):
    if not expect in actual:
        raise Exception('EXPECTED\n"%s"\nBUT FOUND\n"%s"' % (expect, actual))


def check_not(expect, actual):
    if expect in actual:
        raise Exception('EXPECTED\n"%s"\nBUT FOUND\n"%s"' % (expect, actual))


############ TESTS HERE #############

check('SCRIPTSDEV RULEZ', test('extra'))
check('SCRIPTSDEV RULEZ', test('legacy'))
check_not('SCRIPTSDEV RULEZ', test('extra', ['--no-dev']))
check_not('SCRIPTSDEV RULEZ', test('legacy', ['--no-dev']))
