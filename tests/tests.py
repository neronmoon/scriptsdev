import os
from subprocess import check_output, check_call


def test(test, cmds):
    print "Testing:", test, cmds,

    working_dir = '/tmp/scriptsdev/' + test

    check_call(['rm', '-rf', working_dir])
    check_call(['mkdir', '-p', working_dir])

    if isinstance(cmds[0], basestring):
        cmds = [cmds]

    output = ''
    for cmd in cmds:
        source_composer_json = open('tests/%s.json' % test, 'r').read()
        source_composer_json = source_composer_json.replace('<PLUGIN_PATH>', os.getcwd())

        target_composer_json = open(working_dir + '/composer.json', 'w')
        target_composer_json.write(source_composer_json)
        target_composer_json.close()

        try:
            output += check_output(cmd, cwd=working_dir)
        except Exception, e:
            output += str(e.output)
    return output


def check(expect, actual):
    if not expect in actual:
        raise Exception('EXPECTED\n"%s"\nBUT FOUND\n"%s"' % (expect, actual))
    else:
        print 'OK'


def check_not(expect_not, actual):
    if expect_not in actual:
        raise Exception('EXPECTED\n"%s"\nBUT FOUND\n"%s"' % (expect_not, actual))
    else:
        print 'OK'


############ TESTS HERE #############

check('SCRIPTSDEV RULEZ', test('legacy', ['composer', 'update']))
check_not('SCRIPTSDEV RULEZ', test('legacy', ['composer', '--no-dev', 'update']))

check('SCRIPTSDEV RULEZ', test('legacy-with-run-scripts-dev', [
    ['composer', 'update'],
    ['composer', 'run-script', 'test']]))
check('SCRIPTSDEV RULEZ', test('legacy-with-run-scripts-dev', [
    ['composer', 'update'],
    ['composer', '--dev', 'run-script', 'test']]))
check_not('SCRIPTSDEV RULEZ', test('legacy-with-run-scripts-dev', [
    ['composer', 'update'],
    ['composer', '--no-dev', 'run-script', 'test']]))

check('SCRIPTSDEV RULEZ', test('extra', ['composer', 'update']))
check_not('SCRIPTSDEV RULEZ', test('extra', ['composer', '--no-dev', 'update']))

check('SCRIPTSDEV RULEZ', test('extra-with-branch-alias', ['composer', 'update']))
check_not('SCRIPTSDEV RULEZ', test('extra-with-branch-alias', ['composer', '--no-dev', 'update']))

check('SCRIPTSDEV RULEZ', test('extra-with-custom-script', ['composer', 'run-script', 'test-update']))
check_not('SCRIPTSDEV RULEZ', test('extra-with-custom-script', ['composer', 'run-script', 'test-update-no-dev']))

check('SCRIPTSDEV RULEZ', test('extra-with-custom-script', ['composer', 'run-script', 'test-install']))
check_not('SCRIPTSDEV RULEZ', test('extra-with-custom-script', ['composer', 'run-script', 'test-install-no-dev']))

check('SCRIPTSDEV RULEZ', test('extra-with-custom-script-run-script-dev', [
    ['composer', 'update'],
    ['composer', 'run-script', 'test']]))
check('SCRIPTSDEV RULEZ', test('extra-with-custom-script-run-script-dev', [
    ['composer', 'update'],
    ['composer', '--dev', 'run-script', 'test']]))
check_not('SCRIPTSDEV RULEZ', test('extra-with-custom-script-run-script-dev', [
    ['composer', 'update'],
    ['composer', '--no-dev', 'run-script', 'test']]))

check('SCRIPTSDEV RULEZ', test('extra-with-custom-script-run-script-dev', [
    ['composer', 'update'],
    ['composer', 'test']]))
check('SCRIPTSDEV RULEZ', test('extra-with-custom-script-run-script-dev', [
    ['composer', 'update'],
    ['composer', '--dev', 'test']]))
check_not('SCRIPTSDEV RULEZ', test('extra-with-custom-script-run-script-dev', [
    ['composer', 'update'],
    ['composer', '--no-dev', 'test']]))
