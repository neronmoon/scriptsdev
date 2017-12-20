from subprocess import check_output, check_call
import os


def test(test, args=[], cmd=['composer', 'update', '-v'], clean_dir=True):
    working_dir = '/tmp/scriptsdev/' + test

    if clean_dir:
        check_call(['rm', '-rf', working_dir])
        check_call(['mkdir', '-p', working_dir])

    source_composer_json = open('tests/%s.json' % test, 'r').read()
    source_composer_json = source_composer_json.replace('<PLUGIN_PATH>', os.getcwd())

    target_composer_json = open(working_dir + '/composer.json', 'w')
    target_composer_json.write(source_composer_json)
    target_composer_json.close()

    return check_output(cmd + args, cwd=working_dir)


def check(expect, actual):
    print "expect: ", expect
    print "actual: ", actual
    if not expect in actual:
        raise Exception('EXPECTED\n"%s"\nBUT FOUND\n"%s"' % (expect, actual))


def check_not(expect_not, actual):
    print "expect not: ", expect_not
    print "actual: ", actual
    if expect_not in actual:
        raise Exception('EXPECTED\n"%s"\nBUT FOUND\n"%s"' % (expect_not, actual))


############ TESTS HERE #############

check('SCRIPTSDEV RULEZ', test('extra'))
check_not('SCRIPTSDEV RULEZ', test('extra', ['--no-dev']))

check('SCRIPTSDEV RULEZ', test('legacy'))
check_not('SCRIPTSDEV RULEZ', test('legacy', ['--no-dev']))

check('SCRIPTSDEV RULEZ', test('extra-with-custom-script', cmd=['composer', 'run-script', 'test-update']))
check_not('SCRIPTSDEV RULEZ', test('extra-with-custom-script', cmd=['composer', 'run-script', 'test-update-no-dev']))

check('SCRIPTSDEV RULEZ', test('extra-with-custom-script', cmd=['composer', 'run-script', 'test-install'], clean_dir=False))
check_not('SCRIPTSDEV RULEZ', test('extra-with-custom-script', cmd=['composer', 'run-script', 'test-install-no-dev'], clean_dir=False))